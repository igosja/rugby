<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class PollUser
 * @package common\models\db
 *
 * @property int $poll_user_date
 * @property int $poll_user_poll_answer_id
 * @property int $poll_user_user_id
 */
class PollUser extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%poll_user}}';
    }
}
