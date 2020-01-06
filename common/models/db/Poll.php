<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Poll
 * @package common\models\db
 *
 * @property int $poll_id
 * @property int $poll_country_id
 * @property int $poll_date
 * @property string $poll_text
 * @property int $poll_user_id
 * @property int $poll_poll_status_id
 */
class Poll extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%poll}}';
    }
}
