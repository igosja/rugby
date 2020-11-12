<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class StatisticTeam
 * @package common\models\db
 *
 * @property int $id
 * @property int $federation_id
 * @property int $division_id
 * @property int $game
 * @property int $game_no_pass
 * @property int $game_no_score
 * @property int $loose
 * @property int $loose_over
 * @property int $loose_shootout
 * @property int $national_id
 * @property int $pass
 * @property int $penalty_minute
 * @property int $penalty_minute_opponent
 * @property int $score
 * @property int $season_id
 * @property int $team_id
 * @property int $tournament_type_id
 * @property int $win
 * @property int $win_over
 * @property string $win_percent
 * @property int $win_shootout
 *
 * @property-read Team $team
 */
class StatisticTeam extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%statistic_team}}';
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['team_id' => 'team_id']);
    }
}
