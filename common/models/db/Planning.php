<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Planning
 * @package common\models\db
 *
 * @property int $planning_id
 * @property int $planning_player_id
 * @property int $planning_season_id
 * @property int $planning_schedule_id
 * @property int $planning_team_id
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
}
