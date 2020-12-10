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
use Throwable;
use Yii;
use yii\base\Model;
use yii\db\Exception;

/**
 * Class TransferTo
 * @package frontend\models
 *
 * @property int $maxPrice
 * @property int $minPrice
 * @property Player $player
 * @property int $price
 * @property Team $team
 * @property bool $toLeague
 */
class TransferTo extends Model
{
    public $price;
    public $toLeague;
    public $maxPrice = 0;
    public $minPrice = 0;
    public $player;
    public $team;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->maxPrice = $this->player->price * 2;
        $this->minPrice = ceil($this->player->price / 2);
        $this->price = $this->minPrice;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['price', 'toLeague'], 'required'],
            [['toLeague'], 'boolean'],
            [['price'], 'integer', 'min' => $this->minPrice, 'max' => $this->maxPrice],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'price' => 'Начальная цена',
            'toLeague' => 'Продать Лиге',
        ];
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function execute()
    {
        if (!$this->validate()) {
            return false;
        }

        if ($this->player->transfer) {
            Yii::$app->session->setFlash('error', 'Игрок уже выставлен на трансфер.');
            return false;
        }

        if ($this->player->national_id) {
            Yii::$app->session->setFlash('error', 'Нельзя продать игрока сборной.');
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

        if ($this->player->is_no_deal) {
            Yii::$app->session->setFlash(
                'error',
                'Игрока нельзя выставить на трансфер до конца сезона.'
            );
            return false;
        }

        if ($this->player->loan_team_id) {
            Yii::$app->session->setFlash(
                'error',
                'Нельзя выставить на трансфер игроков, отданных в данный момент в аренду.'
            );
            return false;
        }

        $count = Transfer::find()
            ->where(['team_seller_id' => $this->team->id, 'ready' => null])
            ->count();

        if ($count > 5) {
            Yii::$app->session->setFlash(
                'error',
                'Нельзя одновременно выставлять на трансферный рынок более пяти игроков.'
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
                'Нельзя продать полевого игрока, если у вас в команде останется менее двадцати полевых игроков.'
            );
            return false;
        }

        if ($this->player->age < 18) {
            Yii::$app->session->setFlash('error', 'Нельзя продавать игроков младше 18 лет.');
            return false;
        }

        if ($this->player->age > 33) {
            Yii::$app->session->setFlash('error', 'Нельзя продавать игроков старше 34 лет.');
            return false;
        }

        $count = Training::find()
            ->where(['player_id' => $this->player->id, 'ready' => null])
            ->count();

        if ($count) {
            Yii::$app->session->setFlash('error', 'You can not sell a player who is in training.');
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model = new Transfer();
            $model->is_to_league = $this->toLeague;
            $model->player_id = $this->player->id;
            $model->price_seller = $this->price;
            $model->team_seller_id = $this->team->id;
            $model->season_id = Season::getCurrentSeason();
            $model->user_seller_id = Yii::$app->user->id;
            $model->save();

            if ($transaction) {
                $transaction->commit();
            }

            Yii::$app->session->setFlash('success', 'Игрок успешно выставлен на трансфер.');
        } catch (Throwable $e) {
            ErrorHelper::log($e);
            $transaction->rollBack();
            return false;
        }

        return true;
    }
}
