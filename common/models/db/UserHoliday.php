<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class UserHoliday
 * @package common\models\db
 *
 * @property int $user_holiday_id
 * @property int $user_holiday_date_end
 * @property int $user_holiday_date_start
 * @property int $user_holiday_user_id
 */
class UserHoliday extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user_holiday}}';
    }
}
