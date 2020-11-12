<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * Class History
 * @package common\models\db
 *
 * @property int $id
 * @property int $building_id
 * @property int $federation_id
 * @property int $date
 * @property int $fire_reason_id
 * @property int $game_id
 * @property int $history_text_id
 * @property int $national_id
 * @property int $player_id
 * @property int $position_id
 * @property int $season_id
 * @property int $second_team_id
 * @property int $second_user_id
 * @property int $special_id
 * @property int $team_id
 * @property int $user_id
 * @property int $value
 *
 * @property-read Building $building
 * @property-read Federation $federation
 * @property-read FireReason $fireReason
 * @property-read Game $game
 * @property-read HistoryText $historyText
 * @property-read National $national
 * @property-read Player $player
 * @property-read Position $position
 * @property-read Season $season
 * @property-read Team $secondTeam
 * @property-read User $secondUser
 * @property-read Special $special
 * @property-read Team $team
 * @property-read User $user
 */
class History extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%history}}';
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
            [['building_id'], 'integer', 'min' => 1, 'max' => 9],
            [['fire_reason_id', 'history_text_id', 'position_id', 'special_id'], 'integer', 'min' => 1, 'max' => 99],
            [['federation_id', 'national_id'], 'integer', 'min' => 1, 'max' => 999],
            [
                ['game_id', 'player_id', 'second_team_id', 'second_user_id', 'team_id', 'user_id', 'value'],
                'integer',
                'min' => 1
            ],
            [['building_id'], 'exist', 'targetRelation' => 'building'],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
            [['fire_reason_id'], 'exist', 'targetRelation' => 'fireReason'],
            [['game_id'], 'exist', 'targetRelation' => 'game'],
            [['history_text_id'], 'exist', 'targetRelation' => 'historyText'],
            [['national_id'], 'exist', 'targetRelation' => 'national'],
            [['player_id'], 'exist', 'targetRelation' => 'player'],
            [['position_id'], 'exist', 'targetRelation' => 'position'],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['second_team_id'], 'exist', 'targetRelation' => 'secondTeam'],
            [['second_user_id'], 'exist', 'targetRelation' => 'secondUser'],
            [['special_id'], 'exist', 'targetRelation' => 'special'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getBuilding(): ActiveQuery
    {
        return $this->hasOne(Building::class, ['id' => 'building_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getFederation(): ActiveQuery
    {
        return $this->hasOne(Federation::class, ['id' => 'federation_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getFireReason(): ActiveQuery
    {
        return $this->hasOne(FireReason::class, ['id' => 'fire_reason_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getGame(): ActiveQuery
    {
        return $this->hasOne(Game::class, ['id' => 'game_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getHistoryText(): ActiveQuery
    {
        return $this->hasOne(HistoryText::class, ['id' => 'history_text_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getNational(): ActiveQuery
    {
        return $this->hasOne(National::class, ['id' => 'national_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getPlayer(): ActiveQuery
    {
        return $this->hasOne(Player::class, ['id' => 'player_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getPosition(): ActiveQuery
    {
        return $this->hasOne(Position::class, ['id' => 'position_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getSeason(): ActiveQuery
    {
        return $this->hasOne(Season::class, ['id' => 'season_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getSecondTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'second_team_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getSecondUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'second_user_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getSpecial(): ActiveQuery
    {
        return $this->hasOne(Special::class, ['id' => 'special_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->cache();
    }
}
