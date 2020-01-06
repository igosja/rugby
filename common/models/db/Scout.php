<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Scout
 * @package common\models\db
 *
 * @property int $scout_id
 * @property int $scout_is_school
 * @property int $scout_percent
 * @property int $scout_player_id
 * @property int $scout_ready
 * @property int $scout_season_id
 * @property int $scout_style
 * @property int $scout_team_id
 */
class Scout extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%scout}}';
    }
}
