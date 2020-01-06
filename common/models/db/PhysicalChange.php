<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class PhysicalChange
 * @package common\models\db
 *
 * @property int $physical_change_id
 * @property int $physical_change_player_id
 * @property int $physical_change_season_id
 * @property int $physical_change_schedule_id
 * @property int $physical_change_team_id
 */
class PhysicalChange extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%physical_change}}';
    }
}
