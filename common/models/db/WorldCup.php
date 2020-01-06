<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class WorldCup
 * @package common\models\db
 *
 * @property int $world_cup_id
 * @property int $world_cup_bonus_loose
 * @property int $world_cup_bonus_tries
 * @property int $world_cup_difference
 * @property int $world_cup_division_id
 * @property int $world_cup_draw
 * @property int $world_cup_game
 * @property int $world_cup_loose
 * @property int $world_cup_national_id
 * @property int $world_cup_national_type_id
 * @property int $world_cup_place
 * @property int $world_cup_point
 * @property int $world_cup_point_against
 * @property int $world_cup_point_for
 * @property int $world_cup_season_id
 * @property int $world_cup_team_id
 * @property int $world_cup_tries_against
 * @property int $world_cup_tries_for
 * @property int $world_cup_win
 */
class WorldCup extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%world_cup}}';
    }
}
