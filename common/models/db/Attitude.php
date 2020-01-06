<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Attitude
 * @package common\models\db
 *
 * @property int $attitude_id
 * @property string $attitude_name
 * @property int $attitude_order
 */
class Attitude extends AbstractActiveRecord
{
    const NEGATIVE = 1;
    const NEUTRAL = 2;
    const POSITIVE = 3;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%attitude}}';
    }
}
