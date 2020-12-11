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
    public $maxDay = 7;
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
            'maxDay' => 'Дней аренды (max)',
            'minDay' => 'Дней аренды (min)',
            'price' => 'Начальная цена',
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

        if ($this->player->loan) {
            Yii::$app->session->setFlash('error', 'Игрок уже выставлен на арендный рынок.');
            return false;
        }

        if ($this->player->date_no_action > time()) {
            Yii::$app->session->setFlash(
                'error',
                'С игроком нельзя совершать никаких действий до '
                . FormatHelper::asDate($this->player->date_no_action)
                . '.'
            );
            return false;
        }

        if ($this->player->loan_team_id) {
            Yii::$app->session->setFlash('error', 'Нельзя отдавать в аренду игроков, которые уже находятся в аренде.');
            return false;
        }

        $count = Loan::find()
            ->where(['team_seller_id' => $this->team->id, 'ready' => null])
            ->count();

        if ($count > 5) {
            Yii::$app->session->setFlash(
                'error',
                'Нельзя отдавать в аренду более пяти игроков из одной команды одновременно.'
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
                'Нельзя отдать в аренду полевого игрока, если у вас в команде останется менее двадцати полевых игроков.'
            );
            return false;
        }

        $count = Training::find()
            ->where(['player_id' => $this->player->id, 'ready' => null])
            ->count();

        if ($count) {
            Yii::$app->session->setFlash('error', 'Нельзя отдать в аренду игрока, который находится на тренировке.');
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

            Yii::$app->session->setFlash('success', 'Игрок успешно выставлен на арендный рынок.');
        } catch (Exception $e) {
            ErrorHelper::log($e);
            $transaction->rollBack();
            return false;
        }

        return true;
    }
}
