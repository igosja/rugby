<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class StatisticPlayer
 * @package common\models\db
 *
 * @property int $id
 * @property int $assist
 * @property int $assist_power
 * @property int $assist_short
 * @property int $shootout_win
 * @property int $federation_id
 * @property int $division_id
 * @property int $face_off
 * @property float $face_off_percent
 * @property int $face_off_win
 * @property int $game
 * @property int $game_with_shootout
 * @property int $loose
 * @property int $national_id
 * @property int $pass
 * @property float $pass_per_game
 * @property int $penalty
 * @property int $player_id
 * @property int $plus_minus
 * @property int $point
 * @property int $save
 * @property float $save_percent
 * @property int $score
 * @property int $score_draw
 * @property int $score_power
 * @property int $score_short
 * @property float $score_shot_percent
 * @property int $score_win
 * @property int $season_id
 * @property int $shot
 * @property int $shot_gk
 * @property float $shot_per_game
 * @property int $shutout
 * @property int $team_id
 * @property int $tournament_type_id
 * @property int $win
 *
 * @property-read Player $player
 * @property-read Team $team
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

    /**
     * @return ActiveQuery
     */
    public function getPlayer(): ActiveQuery
    {
        return $this->hasOne(Player::class, ['player_id' => 'player_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['team_id' => 'team_id']);
    }
}
