<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class BaseTraining
 * @package common\models\db
 *
 * @property int $base_training_id
 * @property int $base_training_base_level
 * @property int $base_training_build_speed
 * @property int $base_training_level
 * @property int $base_training_position_count
 * @property int $base_training_position_price
 * @property int $base_training_power_count
 * @property int $base_training_power_price
 * @property int $base_training_price_buy
 * @property int $base_training_price_sell
 * @property int $base_training_special_count
 * @property int $base_training_special_price
 * @property int $base_training_training_speed_max
 * @property int $base_training_training_speed_min
 */
class BaseTraining extends AbstractActiveRecord
{
    const START_LEVEL = 1;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%base_training}}';
    }
}
