<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Planning
 * @package common\models\db
 *
 * @property int $id
 * @property int $player_id
 * @property int $season_id
 * @property int $schedule_id
 * @property int $team_id
 *
 * @property-read Player $player
 * @property-read Season $season
 * @property-read Schedule $schedule
 * @property-read Team $team
 */
class Planning extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%planning}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['player_id', 'season_id', 'schedule_id', 'team_id'], 'required'],
            [['season_id'], 'integer', 'min' => 1, 'max' => 999],
            [['player_id', 'schedule_id', 'team_id'], 'integer', 'min' => 9],
            [['player_id'], 'exist', 'targetRelation' => 'player'],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['schedule_id'], 'exist', 'targetRelation' => 'schedule'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
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
    public function getSchedule(): ActiveQuery
    {
        return $this->hasOne(Schedule::class, ['id' => 'schedule_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }
}
