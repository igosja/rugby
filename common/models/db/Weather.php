<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Weather
 * @package common\models\db
 *
 * @property int $weather_id
 * @property string $weather_name
 */
class Weather extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%weather}}';
    }
}
