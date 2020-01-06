<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class League
 * @package common\models\db
 *
 * @property int $league_id
 * @property int $league_bonus_loose
 * @property int $league_bonus_tries
 * @property int $league_difference
 * @property int $league_draw
 * @property int $league_game
 * @property int $league_group
 * @property int $league_loose
 * @property int $league_place
 * @property int $league_point
 * @property int $league_point_against
 * @property int $league_point_for
 * @property int $league_season_id
 * @property int $league_team_id
 * @property int $league_tries_against
 * @property int $league_tries_for
 * @property int $league_win
 */
class League extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%league}}';
    }
}
