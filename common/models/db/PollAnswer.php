<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class PollAnswer
 * @package common\models\db
 *
 * @property int $poll_answer_id
 * @property string $poll_answer_text
 * @property int $poll_answer_poll_id
 */
class PollAnswer extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%poll_answer}}';
    }
}
