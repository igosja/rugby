<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class StatisticTeam
 * @package common\models\db
 *
 * @property int $statistic_team_id
 * @property int $statistic_team_championship_playoff
 * @property int $statistic_team_country_id
 * @property int $statistic_team_division_id
 * @property int $statistic_team_game
 * @property int $statistic_team_game_no_pass
 * @property int $statistic_team_game_no_score
 * @property int $statistic_team_loose
 * @property int $statistic_team_loose_over
 * @property int $statistic_team_loose_shootout
 * @property int $statistic_team_national_id
 * @property int $statistic_team_pass
 * @property int $statistic_team_penalty_minute
 * @property int $statistic_team_penalty_minute_opponent
 * @property int $statistic_team_score
 * @property int $statistic_team_season_id
 * @property int $statistic_team_team_id
 * @property int $statistic_team_tournament_type_id
 * @property int $statistic_team_win
 * @property int $statistic_team_win_over
 * @property string $statistic_team_win_percent
 * @property int $statistic_team_win_shootout
 *
 * @property Team $team
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
        return $this->hasOne(Team::class, ['team_id' => 'statistic_team_team_id']);
    }
}
