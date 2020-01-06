<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class BaseMedical
 * @package common\models\db
 *
 * @property int $base_medical_id
 * @property int $base_medical_base_level
 * @property int $base_medical_build_speed
 * @property int $base_medical_level
 * @property int $base_medical_price_buy
 * @property int $base_medical_price_sell
 * @property int $base_medical_tire
 */
class BaseMedical extends AbstractActiveRecord
{
    const START_LEVEL = 1;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%base_medical}}';
    }
}
