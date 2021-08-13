<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class EventText
 * @package common\models\db
 *
 * @property int $id
 * @property int $event_type_id
 * @property string $text
 *
 * @property-read EventType $eventType
 */
class EventText extends AbstractActiveRecord
{
    public const TRY = 1;
    public const CONVERSION = 2;
    public const PENALTY_KICK = 3;
    public const DROP_GOAL = 4;
    public const YELLOW_CARD = 5;
    public const RED_CARD = 6;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%event_text}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['event_type_id', 'text'], 'required'],
            [['event_type_id'], 'integer', 'min' => 0, 'max' => 9],
            [['text'], 'trim'],
            [['text'], 'string', 'max' => 255],
            [['text'], 'unique'],
            [['event_type_id'], 'exist', 'targetRelation' => 'eventType'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getEventType(): ActiveQuery
    {
        return $this->hasOne(EventType::class, ['id' => 'event_type_id']);
    }
}
