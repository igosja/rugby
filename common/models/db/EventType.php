<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class EventType
 * @package common\models\db
 *
 * @property int $id
 * @property string $text
 */
class EventType extends AbstractActiveRecord
{
    public const TYPE_GOAL = 1;
    public const TYPE_YELLOW = 2;
    public const TYPE_RED = 3;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%event_type}}';
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
