<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class UserBlockReason
 * @package common\models\db
 *
 * @property int $id
 * @property string $text
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
