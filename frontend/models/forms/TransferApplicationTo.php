<?php

// TODO refactor

namespace frontend\models\forms;

use common\components\helpers\ErrorHelper;
use common\models\db\Loan;
use common\models\db\Player;
use common\models\db\Team;
use common\models\db\Transfer;
use common\models\db\TransferApplication;
use common\models\db\User;
use frontend\controllers\AbstractController;
use Throwable;
use Yii;
use yii\base\Model;
use yii\db\Exception;

/**
 * Class TransferApplicationTo
 * @package frontend\models
 *
 * @property int $maxPrice
 * @property int $minPrice
 * @property bool $onlyOne
 * @property Player $player
 * @property int $price
 * @property Team $team
 * @property TransferApplication $transferApplication
 */
class TransferApplicationTo extends Model
{
    public $onlyOne = false;
    public $price = 0;
    public $maxPrice = 0;
    public $minPrice = 0;
    public $player;
    public $team;
    public $transferApplication;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->minPrice = $this->player->transfer->price_seller ?? (int)ceil($this->player->price / 2);
        $this->maxPrice = $this->team->finance ?? $this->minPrice;
        $this->transferApplication = TransferApplication::find()
            ->where([
                'team_id' => $this->team->id ?? 0,
                'transfer_id' => ($this->player->transfer->id ?? 0),
            ])
            ->limit(1)
            ->one();
        if ($this->transferApplication) {
            $this->onlyOne = $this->transferApplication->is_only_one;
            $this->price = $this->transferApplication->price;
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
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
            'onlyOne' => Yii::t('frontend', 'models.forms.loan-application-to.label.only'),
            'price' => Yii::t('frontend', 'models.forms.loan-application-to.label.price'),
        ];
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function execute(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $transfer = Transfer::find()
            ->where(['player_id' => $this->player->id, 'ready' => null])
            ->limit(1)
            ->one();
        if (!$transfer) {
            return false;
        }

        if ($transfer->team_seller_id === $this->team->id) {
            Yii::$app->session->setFlash('error', Yii::t('frontend', 'models.forms.loan-application-to.execute.error.team'));
            return false;
        }

        if ($transfer->user_seller_id === Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', Yii::t('frontend', 'models.forms.loan-application-to.execute.error.team'));
            return false;
        }

        $check = TransferApplication::find()
            ->where(['id' => $transfer->id, 'user_id' => Yii::$app->user->id])
            ->andFilterWhere(['!=', 'id', ($this->transferApplication->id ?? null)])
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

        $teamArray = [0];

        /**
         * @var Transfer[] $transferArray
         */
        $transferArray = Transfer::find()
            ->where(['season_id' => $controller->season->id])
            ->andWhere(['not', ['ready' => null]])
            ->andWhere(['!=', 'team_buyer_id', 0])
            ->andWhere(['!=', 'team_seller_id', 0])
            ->andWhere([
                'or',
                ['team_buyer_id' => $transfer->team_seller_id],
                ['team_seller_id' => $transfer->team_seller_id]
            ])
            ->all();

        foreach ($transferArray as $item) {
            if (!in_array($item->team_buyer_id, [0, $transfer->team_seller_id], true)) {
                $teamArray[] = $item->team_buyer_id;
            }

            if (!in_array($item->team_seller_id, [0, $transfer->team_seller_id], true)) {
                $teamArray[] = $item->team_seller_id;
            }
        }

        /**
         * @var Loan[] $loanArray
         */
        $loanArray = Loan::find()
            ->where(['season_id' => $controller->season->id])
            ->andWhere(['not', ['ready' => null]])
            ->andWhere(['!=', 'team_buyer_id', 0])
            ->andWhere(['!=', 'team_seller_id', 0])
            ->andWhere([
                'or',
                ['team_buyer_id' => $transfer->team_seller_id],
                ['team_seller_id' => $transfer->team_seller_id]
            ])
            ->all();

        foreach ($loanArray as $item) {
            if (!in_array($item->team_buyer_id, [0, $transfer->team_seller_id], true)) {
                $teamArray[] = $item->team_buyer_id;
            }

            if (!in_array($item->team_seller_id, [0, $transfer->team_seller_id], true)) {
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
            ->andWhere(['not', ['ready' => null]])
            ->andWhere(['!=', 'user_buyer_id', 0])
            ->andWhere(['!=', 'user_seller_id', 0])
            ->andWhere([
                'or',
                ['user_buyer_id' => $transfer->user_seller_id],
                ['user_seller_id' => $transfer->user_seller_id]
            ])
            ->all();

        foreach ($transferArray as $item) {
            if (!in_array($item->user_buyer_id, [0, $transfer->user_seller_id], true)) {
                $userArray[] = $item->user_buyer_id;
            }

            if (!in_array($item->user_seller_id, [0, $transfer->user_seller_id], true)) {
                $userArray[] = $item->user_seller_id;
            }
        }

        $loanArray = Loan::find()
            ->where(['season_id' => $controller->season->id])
            ->andWhere(['not', ['ready' => null]])
            ->andWhere(['!=', 'user_buyer_id', 0])
            ->andWhere(['!=', 'user_seller_id', 0])
            ->andWhere([
                'or',
                ['user_buyer_id' => $transfer->user_seller_id],
                ['user_seller_id' => $transfer->user_seller_id]
            ])
            ->all();

        foreach ($loanArray as $item) {
            if (!in_array($item->user_buyer_id, [0, $transfer->user_seller_id], true)) {
                $userArray[] = $item->user_buyer_id;
            }

            if (!in_array($item->user_seller_id, [0, $transfer->user_seller_id], true)) {
                $userArray[] = $item->user_seller_id;
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
            $model = $this->transferApplication;
            if (!$model) {
                $model = new TransferApplication();
                $model->team_id = $this->team->id;
                $model->transfer_id = $transfer->id;
                $model->user_id = Yii::$app->user->id;
            }
            $model->is_only_one = $this->onlyOne;
            $model->price = $this->price;
            $model->save();

            if ($transaction) {
                $transaction->commit();
            }

            Yii::$app->session->setFlash('success', Yii::t('frontend', 'models.forms.loan-application-to.execute.success'));
        } catch (Throwable $e) {
            ErrorHelper::log($e);
            $transaction->rollBack();
            return false;
        }

        return true;
    }
}
