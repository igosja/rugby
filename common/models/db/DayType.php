<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class DayType
 * @package common\models\db
 *
 * @property int $day_type_id
 * @property string $day_type_name
 * @property string $day_type_text
 */
class DayType extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%day_type}}';
    }
}
