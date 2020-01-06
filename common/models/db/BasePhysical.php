<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class BasePhysical
 * @package common\models\db
 *
 * @property int $base_physical_id
 * @property int $base_physical_base_level
 * @property int $base_physical_build_speed
 * @property int $base_physical_change_count
 * @property int $base_physical_level
 * @property int $base_physical_price_buy
 * @property int $base_physical_price_sell
 * @property int $base_physical_tire_bonus
 */
class BasePhysical extends AbstractActiveRecord
{
    const START_LEVEL = 1;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%base_physical}}';
    }
}
