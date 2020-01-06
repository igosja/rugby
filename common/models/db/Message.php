<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Message
 * @package common\models\db
 *
 * @property int $message_id
 * @property int $message_date
 * @property int $message_read
 * @property string $message_text
 * @property int $message_from_user_id
 * @property int $message_to_user_id
 */
class Message extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%message}}';
    }
}
