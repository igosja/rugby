<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Lineup
 * @package common\models\db
 *
 * @property int $lineup_id
 * @property int $lineup_age
 * @property int $lineup_captain
 * @property int $lineup_conversion
 * @property int $lineup_drop_goal
 * @property int $lineup_game_id
 * @property int $lineup_minute
 * @property int $lineup_national_id
 * @property int $lineup_penalty
 * @property int $lineup_player_id
 * @property int $lineup_plus_minus
 * @property int $lineup_point
 * @property int $lineup_position_id
 * @property int $lineup_power_change
 * @property int $lineup_power_nominal
 * @property int $lineup_power_real
 * @property int $lineup_red_card
 * @property int $lineup_team_id
 * @property int $lineup_try
 * @property int $lineup_yellow_card
 *
 * @property Game $game
 * @property LineupSpecial[] $lineupSpecial
 * @property Position $position
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

    public function iconCaptain(): string
    {
        $result = '';
        if ($this->lineup_captain) {
            $result = '<i class="fa fa-copyright" title="Captain"></i>';
        }
        return $result;
    }

    /**
     * @return string
     */
    public function special(): string
    {
        $result = [];
        foreach ($this->lineupSpecial as $special) {
            $result[] = $special->special->special_name . $special->lineup_special_level;
        }
        return implode(' ', $result);
    }

    /**
     * @return string
     */
    public function iconPowerChange(): string
    {
        $result = '';
        if ($this->lineup_power_change > 0) {
            $result = '<i class="fa fa-plus-square-o font-green" title="+1 балл по результатам матча"></i>';
        } elseif ($this->lineup_power_change < 0) {
            $result = '<i class="fa fa-minus-square-o font-red" title="-1 балл по результатам матча"></i>';
        }
        return $result;
    }

    /**
     * @return ActiveQuery
     */
    public function getGame(): ActiveQuery
    {
        return $this->hasOne(Game::class, ['game_id' => 'lineup_game_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLineupSpecial(): ActiveQuery
    {
        return $this->hasMany(LineupSpecial::class, ['lineup_special_lineup_id' => 'lineup_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPosition(): ActiveQuery
    {
        return $this->hasOne(Position::class, ['position_id' => 'lineup_position_id']);
    }
}
