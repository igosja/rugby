<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class NationalPlayerDay
 * @package common\models\db
 *
 * @property int $id
 * @property int $day
 * @property int $national_id
 * @property int $player_id
 * @property int $season_id
 * @property int $team_id
 *
 * @property-read National $national
 * @property-read Player $player
 * @property-read Season $season
 * @property-read Team $team
 */
class NationalPlayerDay extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%national_player_day}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['day', 'national_id', 'player_id', 'season_id', 'team_id'], 'required'],
            [['day', 'national_id', 'season_id'], 'integer', 'min' => 1, 'max' => 999],
            [['player_id', 'team_id'], 'integer', 'min' => 1],
            [['national_id'], 'exist', 'targetRelation' => 'national'],
            [['player_id'], 'exist', 'targetRelation' => 'player'],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
        ];
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
    public function getSeason(): ActiveQuery
    {
        return $this->hasOne(Season::class, ['id' => 'season_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }
}
