<?php

namespace common\models\db;

use codeonyii\yii2validators\AtLeastValidator;
use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Lineup
 * @package common\models\db
 *
 * @property int $id
 * @property int $age
 * @property int $conversion
 * @property int $drop_goal
 * @property int $game_id
 * @property bool $is_captain
 * @property int $minute
 * @property int $national_id
 * @property int $player_id
 * @property int $point
 * @property int $position_id
 * @property int $power_change
 * @property int $power_nominal
 * @property int $power_real
 * @property int $red_card
 * @property int $team_id
 * @property int $try
 * @property int $yellow_card
 *
 * @property-read Game $game
 * @property-read National $national
 * @property-read Player $player
 * @property-read Position $position
 * @property-read Team $team
 */
class Lineup extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%lineup}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['game_id', 'player_id', 'position_id'], 'required'],
            [['national_id'], AtLeastValidator::class, 'in' => ['national_id', 'team_id']],
            [['is_captain'], 'boolean'],
            [['drop_goal', 'red_card', 'try', 'yellow_card'], 'integer', 'min' => 0, 'max' => 9],
            [['conversion', 'minute', 'point', 'position_id'], 'integer', 'min' => 0, 'max' => 99],
            [['national_id', 'power_nominal', 'power_real', 'season_id'], 'integer', 'min' => 0, 'max' => 999],
            [['game_id', 'player_id', 'team_id'], 'integer', 'min' => 1],
            [['game_id'], 'exist', 'targetRelation' => 'game'],
            [['national_id'], 'exist', 'targetRelation' => 'national'],
            [['player_id'], 'exist', 'targetRelation' => 'player'],
            [['position_id'], 'exist', 'targetRelation' => 'position'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getGame(): ActiveQuery
    {
        return $this->hasOne(Game::class, ['id' => 'game_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getNational(): ActiveQuery
    {
        return $this->hasOne(National::class, ['id' => 'national_id']);
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
    public function getPosition(): ActiveQuery
    {
        return $this->hasOne(Position::class, ['id' => 'position_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }
}
