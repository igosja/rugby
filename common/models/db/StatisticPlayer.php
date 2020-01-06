<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class StatisticPlayer
 * @package common\models\db
 *
 * @property int $statistic_player_id
 * @property int $statistic_player_assist
 * @property int $statistic_player_assist_power
 * @property int $statistic_player_assist_short
 * @property int $statistic_player_shootout_win
 * @property int $statistic_player_championship_playoff
 * @property int $statistic_player_country_id
 * @property int $statistic_player_division_id
 * @property int $statistic_player_face_off
 * @property float $statistic_player_face_off_percent
 * @property int $statistic_player_face_off_win
 * @property int $statistic_player_game
 * @property int $statistic_player_game_with_shootout
 * @property int $statistic_player_is_gk
 * @property int $statistic_player_loose
 * @property int $statistic_player_national_id
 * @property int $statistic_player_pass
 * @property float $statistic_player_pass_per_game
 * @property int $statistic_player_penalty
 * @property int $statistic_player_player_id
 * @property int $statistic_player_plus_minus
 * @property int $statistic_player_point
 * @property int $statistic_player_save
 * @property float $statistic_player_save_percent
 * @property int $statistic_player_score
 * @property int $statistic_player_score_draw
 * @property int $statistic_player_score_power
 * @property int $statistic_player_score_short
 * @property float $statistic_player_score_shot_percent
 * @property int $statistic_player_score_win
 * @property int $statistic_player_season_id
 * @property int $statistic_player_shot
 * @property int $statistic_player_shot_gk
 * @property float $statistic_player_shot_per_game
 * @property int $statistic_player_shutout
 * @property int $statistic_player_team_id
 * @property int $statistic_player_tournament_type_id
 * @property int $statistic_player_win
 */
class StatisticPlayer extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%statistic_player}}';
    }
}
