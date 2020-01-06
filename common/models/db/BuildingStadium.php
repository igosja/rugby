<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class BuildingStadium
 * @package common\models\db
 *
 * @property int $building_stadium_id
 * @property int $building_stadium_capacity
 * @property int $building_stadium_construction_type_id
 * @property int $building_stadium_date
 * @property int $building_stadium_day
 * @property int $building_stadium_ready
 * @property int $building_stadium_team_id
 */
class BuildingStadium extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%building_stadium}}';
    }
}
