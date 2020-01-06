<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Base
 * @package common\models\db
 *
 * @property int $base_id
 * @property int $base_build_speed
 * @property int $base_level
 * @property int $base_maintenance_base
 * @property int $base_maintenance_slot
 * @property int $base_price_buy
 * @property int $base_price_sell
 * @property int $base_slot_max
 * @property int $base_slot_min
 */
class Base extends AbstractActiveRecord
{
    const START_LEVEL = 2;
    const FREE_SLOTS = 5;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%base}}';
    }
}
