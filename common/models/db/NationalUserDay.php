<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class NationalUserDay
 * @package common\models\db
 *
 * @property int $national_user_day_id
 * @property int $national_user_day_day
 * @property int $national_user_day_national_id
 * @property int $national_user_day_user_id
 */
class NationalUserDay extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%national_user_day}}';
    }
}
