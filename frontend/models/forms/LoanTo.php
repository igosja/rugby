<?php

// TODO refactor

namespace frontend\models\forms;

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Loan;
use common\models\db\Player;
use common\models\db\Season;
use common\models\db\Team;
use common\models\db\Training;
use common\models\db\Transfer;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class LoanTo
 * @package frontend\models
 *
 * @property int $maxDay
 * @property int $maxPrice
 * @property int $minDay
 * @property int $minPrice
 * @property Player $player
 * @property int $price
 * @property Team $team
 */
class LoanTo extends Model
{
    public $price;
    public $maxDay = 99;
    public $maxPrice = 0;
    public $minDay = 1;
    public $minPrice = 0;
    public $player;
    public $team;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->maxPrice = ceil($this->player->price / 100);
        $this->minPrice = ceil($this->player->price / 1000);
        $this->price = $this->minPrice;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['maxDay', 'minDay'], 'integer', 'min' => 1, 'max' => 7],
            [['maxDay'], 'compare', 'compareAttribute' => 'minDay', 'operator' => '>='],
            [['price'], 'integer', 'min' => $this->minPrice, 'max' => $this->maxPrice],
            [['price', 'maxDay', 'minDay'], 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'maxDay' => Yii::t('frontend', 'models.forms.loan-to.label.day.max'),
            'minDay' => Yii::t('frontend', 'models.forms.loan-to.label.day.min'),
            'price' => Yii::t('frontend', 'models.forms.loan-to.label.price'),
        ];
    }

    /**
     * @return bool
     */
    public function execute(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        if ($this->player->loan) {
            Yii::$app->session->setFlash('error', Yii::t('frontend', 'models.forms.loan-to.execute.error.loan'));
            return false;
        }

        if ($this->player->age >= Player::AGE_READY_FOR_PENSION) {
            Yii::$app->session->setFlash('error', Yii::t('frontend', 'models.forms.loan-to.execute.error.age', [
                'age' => $this->player->age,
            ]));
            return false;
        }

        if ($this->player->date_no_action > time()) {
            Yii::$app->session->setFlash(
                'error',
                Yii::t('frontend', 'models.forms.loan-to.execute.error.no-action', [
                    'date' => FormatHelper::asDate($this->player->date_no_action),
                ])
            );
            return false;
        }

        if ($this->player->loan_team_id) {
            Yii::$app->session->setFlash('error', Yii::t('frontend', 'models.forms.loan-to.execute.error.loan_team_id'));
            return false;
        }

        $count = Loan::find()
            ->where(['team_seller_id' => $this->team->id, 'ready' => null])
            ->count();

        if ($count > 5) {
            Yii::$app->session->setFlash(
                'error',
                Yii::t('frontend', 'models.forms.loan-to.execute.error.count')
            );
            return false;
        }

        $countTeam = Player::find()
            ->where(['loan_team_id' => null, 'team_id' => $this->team->id])
            ->count();

        $countTransfer = Transfer::find()
            ->where(['team_seller_id' => $this->team->id, 'ready' => null])
            ->count();

        $countLoan = Loan::find()
            ->where(['team_seller_id' => $this->team->id, 'ready' => null])
            ->count();

        $count = $countTeam - ($countTransfer + $countLoan);

        if ($count < 25) {
            Yii::$app->session->setFlash(
                'error',
                Yii::t('frontend', 'models.forms.loan-to.execute.error.player')
            );
            return false;
        }

        $count = Training::find()
            ->where(['player_id' => $this->player->id, 'ready' => null])
            ->count();

        if ($count) {
            Yii::$app->session->setFlash('error', Yii::t('frontend', 'models.forms.loan-to.execute.error.training'));
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model = new Loan();
            $model->day_max = $this->maxDay;
            $model->day_min = $this->minDay;
            $model->player_id = $this->player->id;
            $model->price_seller = $this->price;
            $model->season_id = Season::getCurrentSeason();
            $model->team_seller_id = $this->team->id;
            $model->user_seller_id = Yii::$app->user->id;
            $model->save();

            if ($transaction) {
                $transaction->commit();
            }

            Yii::$app->session->setFlash('success', Yii::t('frontend', 'models.forms.loan-to.execute.success'));
        } catch (Exception $e) {
            ErrorHelper::log($e);
            $transaction->rollBack();
            return false;
        }

        return true;
    }
}
