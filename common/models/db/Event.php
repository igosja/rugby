<?php

namespace common\models\db;

use codeonyii\yii2validators\AtLeastValidator;
use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Event
 * @package common\models\db
 *
 * @property int $id
 * @property int $event_text_id
 * @property int $game_id
 * @property int $guest_points
 * @property int $home_points
 * @property int $minute
 * @property int $national_id
 * @property int $player_id
 * @property int $team_id
 *
 * @property-read EventText $eventText
 */
class Event extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%event}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['event_text_id', 'game_id', 'minute', 'player_id'], 'required'],
            [['national_id'], AtLeastValidator::class, 'in' => ['national_id', 'team_id']],
            [
                ['guest_points', 'home_points'],
                'required',
                'when' => function () {
                    return EventType::TYPE_GOAL === $this->eventText->event_type_id;
                }
            ],
            [['event_text_id'], 'integer', 'min' => 0, 'max' => 9],
            [['guest_points', 'home_points'], 'integer', 'min' => 0, 'max' => 999],
            [['minute'], 'integer', 'min' => 0, 'max' => 99],
            [['national_id'], 'integer', 'min' => 0, 'max' => 99999],
            [['event_text_id'], 'exist', 'targetRelation' => 'eventText'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getEventText(): ActiveQuery
    {
        return $this->hasOne(EventText::class, ['id' => 'event_text_id']);
    }
}
