<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class UserBlockType
 * @package common\models\db
 *
 * @property int $user_block_type_id
 * @property string $user_block_type_text
 */
class UserBlockType extends AbstractActiveRecord
{
    const TYPE_SITE = 1;
    const TYPE_CHAT = 2;
    const TYPE_COMMENT = 3;
    const TYPE_COMMENT_DEAL = 4;
    const TYPE_COMMENT_GAME = 5;
    const TYPE_COMMENT_NEWS = 6;
    const TYPE_FORUM = 7;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user_block_type}}';
    }
}
