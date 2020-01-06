<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class BaseSchool
 * @package common\models\db
 *
 * @property int $base_school_id
 * @property int $base_school_base_level
 * @property int $base_school_build_speed
 * @property int $base_school_level
 * @property int $base_school_player_count
 * @property int $base_school_power
 * @property int $base_school_price_buy
 * @property int $base_school_price_sell
 * @property int $base_school_school_speed
 * @property int $base_school_with_special
 * @property int $base_school_with_style
 */
class BaseSchool extends AbstractActiveRecord
{
    const START_LEVEL = 1;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%base_school}}';
    }
}
