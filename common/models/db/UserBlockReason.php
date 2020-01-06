<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class UserBlockReason
 * @package common\models\db
 *
 * @property int $user_block_reason_id
 * @property string $user_block_reason_text
 */
class UserBlockReason extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user_block_reason}}';
    }
}
