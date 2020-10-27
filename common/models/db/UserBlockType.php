<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class UserBlockType
 * @package common\models\db
 *
 * @property int $id
 * @property string $text
 */
class UserBlockType extends AbstractActiveRecord
{
    public const TYPE_SITE = 1;
    public const TYPE_CHAT = 2;
    public const TYPE_COMMENT = 3;
    public const TYPE_COMMENT_DEAL = 4;
    public const TYPE_COMMENT_GAME = 5;
    public const TYPE_COMMENT_NEWS = 6;
    public const TYPE_FORUM = 7;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user_block_type}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['text'], 'required'],
            [['text'], 'trim'],
            [['text'], 'string', 'max' => 255],
            [['text'], 'unique'],
        ];
    }
}
