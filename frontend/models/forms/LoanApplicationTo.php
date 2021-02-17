<?php

// TODO refactor

namespace frontend\models\forms;

use common\components\helpers\ErrorHelper;
use common\models\db\Loan;
use common\models\db\LoanApplication;
use common\models\db\Player;
use common\models\db\Team;
use common\models\db\Transfer;
use common\models\db\User;
use Exception;
use frontend\controllers\AbstractController;
use Yii;
use yii\base\Model;

/**
 * Class LoanApplicationTo
 * @package frontend\models
 *
 * @property int $day
 * @property LoanApplication $loanApplication
 * @property int $maxDay
 * @property int $maxPrice
 * @property int $minDay
 * @property int $minPrice
 * @property bool $onlyOne
 * @property Player $player
 * @property int $price
 * @property Team $team
 */
class LoanApplicationTo extends Model
{
    public $day = 0;
    public $onlyOne = false;
    public $price = 0;
    public $maxDay = 0;
    public $maxPrice = 0;
    public $minDay = 0;
    public $minPrice = 0;
    public $player;
    public $team;
    public $loanApplication;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->minPrice = $this->player->loan->price_seller ?? (int)ceil($this->player->price / 1000);
        $this->maxPrice = $this->team->finance ?? 0;
        $this->minDay = $this->player->loan->day_min ?? 1;
        $this->maxDay = $this->player->loan->day_max ?? 7;
        $this->loanApplication = LoanApplication::find()
            ->where([
                'team_id' => $this->team->id ?? 0,
                'loan_id' => $this->player->loan->id ?? 0,
            ])
            ->limit(1)
            ->one();
        if ($this->loanApplication) {
            $this->day = $this->loanApplication->day;
            $this->onlyOne = $this->loanApplication->is_only_one;
            $this->price = $this->loanApplication->price;
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['day'], 'integer', 'min' => $this->minDay, 'max' => $this->maxDay],
            [['onlyOne'], 'boolean'],
            [['price'], 'integer', 'min' => $this->minPrice, 'max' => $this->maxPrice],
            [['onlyOne', 'price'], 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'day' => Yii::t('frontend', 'models.forms.loan-application-to.label.day'),
            'onlyOne' => Yii::t('frontend', 'models.forms.loan-application-to.label.only'),
            'price' => Yii::t('frontend', 'models.forms.loan-application-to.label.price'),
        ];
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function execute()
    {
        if (!$this->validate()) {
            return false;
        }

        $loan = Loan::find()
            ->where(['player_id' => $this->player->id, 'ready' => null])
            ->one();
        if (!$loan) {
            return false;
        }

        if ($loan->team_seller_id === $this->team->id) {
            Yii::$app->session->setFlash('error', Yii::t('frontend', 'models.forms.loan-application-to.execute.error.team'));
            return false;
        }

        if ($loan->user_seller_id === Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', Yii::t('frontend', 'models.forms.loan-application-to.execute.error.team'));
            return false;
        }

        $check = LoanApplication::find()
            ->where(['loan_id' => $loan->id, 'user_id' => Yii::$app->user->id])
            ->andFilterWhere(['!=', 'id', $this->loanApplication->id ?? null])
            ->count();
        if ($check) {
            Yii::$app->session->setFlash('error', Yii::t('frontend', 'models.forms.loan-application-to.execute.error.application'));
            return false;
        }

        /** @var AbstractController $controller */
        $controller = Yii::$app->controller;

        $check = User::find()
            ->where(['id' => $controller->user->referrer_user_id])
            ->andWhere(['id' => $this->player->team->user_id])
            ->count();
        if ($check) {
            Yii::$app->session->setFlash('error', Yii::t('frontend', 'models.forms.loan-application-to.execute.error.referral'));
            return false;
        }

        $check = User::find()
            ->where(['id' => $this->player->team->user_id])
            ->andWhere(['referrer_user_id' => $controller->user->id])
            ->count();
        if ($check) {
            Yii::$app->session->setFlash('error', Yii::t('frontend', 'models.forms.loan-application-to.execute.error.referral'));
            return false;
        }

        $check = Team::find()
            ->where(['id' => $this->player->team_id])
            ->andWhere([
                'id' => Team::find()
                    ->select(['id'])
                    ->where([
                        'user_id' => User::find()
                            ->select(['user_id'])
                            ->where(['referrer_user_id' => $controller->user->id])
                    ])
            ])
            ->count();
        if ($check) {
            Yii::$app->session->setFlash('error', Yii::t('frontend', 'models.forms.loan-application-to.execute.error.referral'));
            return false;
        }

        $teamArray = [0];

        /**
         * @var Transfer[] $transferArray
         */
        $transferArray = Transfer::find()
            ->where(['season_id' => $controller->season->id])
            ->andWhere(['not', ['ready' => 0]])
            ->andWhere(['!=', 'team_buyer_id', 0])
            ->andWhere(['!=', 'team_seller_id', 0])
            ->andWhere([
                'or',
                ['team_buyer_id' => $loan->team_seller_id],
                ['team_seller_id' => $loan->team_seller_id]
            ])
            ->all();

        foreach ($transferArray as $item) {
            if (!in_array($item->team_buyer_id, [0, $loan->team_seller_id], true)) {
                $teamArray[] = $item->team_buyer_id;
            }

            if (!in_array($item->team_seller_id, [0, $loan->team_seller_id], true)) {
                $teamArray[] = $item->team_seller_id;
            }
        }

        /**
         * @var Loan[] $loanArray
         */
        $loanArray = Loan::find()
            ->where(['season_id' => $controller->season->id])
            ->andWhere(['not', ['ready' => 0]])
            ->andWhere(['!=', 'team_buyer_id', 0])
            ->andWhere(['!=', 'team_seller_id', 0])
            ->andWhere([
                'or',
                ['team_buyer_id' => $loan->team_seller_id],
                ['team_seller_id' => $loan->team_seller_id]
            ])
            ->all();

        foreach ($loanArray as $item) {
            if (!in_array($item->team_buyer_id, [0, $loan->team_seller_id], true)) {
                $teamArray[] = $item->team_buyer_id;
            }

            if (!in_array($item->team_seller_id, [0, $loan->team_seller_id], true)) {
                $teamArray[] = $item->team_seller_id;
            }
        }

        if (in_array($this->team->id, $teamArray, true)) {
            Yii::$app->session->setFlash('error', Yii::t('frontend', 'models.forms.loan-application-to.execute.error.season.team'));
            return false;
        }

        $userArray = [0];

        $transferArray = Transfer::find()
            ->where(['season_id' => $controller->season->id])
            ->andWhere(['not', ['ready' => 0]])
            ->andWhere(['!=', 'user_buyer_id', 0])
            ->andWhere(['!=', 'user_seller_id', 0])
            ->andWhere([
                'or',
                ['user_buyer_id' => $loan->user_seller_id],
                ['user_seller_id' => $loan->user_seller_id]
            ])
            ->all();

        foreach ($transferArray as $item) {
            if (!in_array($item->user_buyer_id, [0, $loan->user_seller_id], true)) {
                $userArray[] = $item->user_buyer_id;
            }

            if (!in_array($item->user_seller_id, [0, $loan->user_seller_id], true)) {
                $userArray[] = $item->user_seller_id;
            }
        }

        $loanArray = Loan::find()
            ->where(['season_id' => $controller->season->id])
            ->andWhere(['not', ['ready' => 0]])
            ->andWhere(['!=', 'user_buyer_id', 0])
            ->andWhere(['!=', 'user_seller_id', 0])
            ->andWhere([
                'or',
                ['user_buyer_id' => $loan->user_seller_id],
                ['user_seller_id' => $loan->user_seller_id]
            ])
            ->all();

        foreach ($loanArray as $item) {
            if (!in_array($item->user_buyer_id, [0, $loan->user_seller_id], true)) {
                $userArray[] = $item->loan_user_buyer_id;
            }

            if (!in_array($item->user_seller_id, [0, $loan->user_seller_id], true)) {
                $userArray[] = $item->loan_user_seller_id;
            }
        }

        if (in_array(Yii::$app->user->id, $userArray, true)) {
            Yii::$app->session->setFlash(
                'error',
                Yii::t('frontend', 'models.forms.loan-application-to.execute.error.season.user')
            );
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model = $this->loanApplication;
            if (!$model) {
                $model = new LoanApplication();
                $model->team_id = $this->team->id;
                $model->loan_id = $loan->id;
                $model->user_id = Yii::$app->user->id;
            }
            $model->day = $this->day;
            $model->is_only_one = $this->onlyOne;
            $model->price = $this->price;
            $model->save();

            if ($transaction) {
                $transaction->commit();
            }

            Yii::$app->session->setFlash('success', Yii::t('frontend', 'models.forms.loan-application-to.execute.success'));
        } catch (Exception $e) {
            ErrorHelper::log($e);
            $transaction->rollBack();
            return false;
        }

        return true;
    }
}
