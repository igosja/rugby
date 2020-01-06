<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class BuildingBase
 * @package common\models\db
 *
 * @property int $building_base_id
 * @property int $building_base_building_id
 * @property int $building_base_construction_type_id
 * @property int $building_base_date
 * @property int $building_base_day
 * @property int $building_base_ready
 * @property int $building_base_team_id
 */
class BuildingBase extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%building_base}}';
    }
}
