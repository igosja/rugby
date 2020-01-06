<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class TransferComment
 * @package common\models\db
 *
 * @property int $transfer_comment_id
 * @property int $transfer_comment_check
 * @property int $transfer_comment_date
 * @property int $transfer_comment_transfer_id
 * @property string $transfer_comment_text
 * @property int $transfer_comment_user_id
 */
class TransferComment extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%transfer_comment}}';
    }
}
