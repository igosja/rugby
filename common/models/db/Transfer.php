<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * Class Transfer
 * @package common\models\db
 *
 * @property int $id
 * @property int $age
 * @property int $cancel
 * @property int $date
 * @property bool $is_to_league
 * @property int $player_id
 * @property int $player_price
 * @property int $power
 * @property int $price_buyer
 * @property int $price_seller
 * @property int $ready
 * @property int $season_id
 * @property int $team_buyer_id
 * @property int $team_seller_id
 * @property int $user_buyer_id
 * @property int $user_seller_id
 * @property int $voted
 *
 * @property-read Player $player
 * @property-read Season $season
 * @property-read Team $teamBuyer
 * @property-read Team $teamSeller
 * @property-read User $userBuyer
 * @property-read User $userSeller
 */
class Transfer extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%transfer}}';
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['player_id', 'price_seller', 'team_seller_id', 'user_seller_id'], 'required'],
            [['age'], 'integer', 'min' => 1, 'max' => 99],
            [['power', 'season_id'], 'integer', 'min' => 1, 'max' => 999],
            [
                [
                    'cancel',
                    'user_buyer_id',
                    'user_seller_id',
                    'team_buyer_id',
                    'team_seller_id',
                    'voted',
                ],
                'integer',
                'min' => 0
            ],
            [
                [
                    'player_id',
                    'player_price',
                    'price_buyer',
                    'price_seller',
                    'ready',
                ],
                'integer',
                'min' => 1
            ],
            [['player_id'], 'exist', 'targetRelation' => 'player'],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['team_buyer_id'], 'exist', 'targetRelation' => 'teamBuyer'],
            [['team_seller_id'], 'exist', 'targetRelation' => 'teamSeller'],
            [['user_buyer_id'], 'exist', 'targetRelation' => 'userBuyer'],
            [['user_seller_id'], 'exist', 'targetRelation' => 'userSeller'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getPlayer(): ActiveQuery
    {
        return $this->hasOne(Player::class, ['id' => 'player_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSeason(): ActiveQuery
    {
        return $this->hasOne(Season::class, ['id' => 'season_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeamBuyer(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_buyer_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeamSeller(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_seller_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserBuyer(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_buyer_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserSeller(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_seller_id']);
    }
}
