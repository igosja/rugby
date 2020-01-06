<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Complaint
 * @package common\models\db
 *
 * @property int $complaint_id
 * @property int $complaint_date
 * @property int $complaint_chat_id
 * @property int $complaint_forum_message_id
 * @property int $complaint_game_comment_id
 * @property int $complaint_loan_comment_id
 * @property int $complaint_news_comment_id
 * @property int $complaint_ready
 * @property int $complaint_transfer_comment_id
 * @property int $complaint_user_id
 */
class Complaint extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%complaint}}';
    }
}
