<?php

// TODO refactor

namespace common\models\db;

use codeonyii\yii2validators\AtLeastValidator;
use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;
use yii\db\Exception;

/**
 * Class Event
 * @package common\models\db
 *
 * @property int $id
 * @property int $event_text_id
 * @property int $game_id
 * @property int $guest_point
 * @property int $home_point
 * @property int $minute
 * @property int $national_id
 * @property int $player_id
 * @property int $team_id
 *
 * @property-read EventText $eventText
 * @property-read Game $game
 * @property-read National $national
 * @property-read Player $player
 * @property-read Team $team
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
                ['guest_point', 'home_point'],
                'required',
                'when' => function () {
                    return EventType::TYPE_GOAL === $this->eventText->event_type_id;
                }
            ],
            [['event_text_id'], 'integer', 'min' => 0, 'max' => 9],
            [['guest_point', 'home_point'], 'integer', 'min' => 0, 'max' => 999],
            [['minute'], 'integer', 'min' => 0, 'max' => 99],
            [['national_id'], 'integer', 'min' => 0, 'max' => 99999],
            [['event_text_id'], 'exist', 'targetRelation' => 'eventText'],
            [['game_id'], 'exist', 'targetRelation' => 'game'],
            [['national_id'], 'exist', 'targetRelation' => 'national'],
            [['player_id'], 'exist', 'targetRelation' => 'player'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
        ];
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public static function log(array $data): bool
    {
        $event = new self();
        $event->setAttributes($data);
        return $event->save();
    }

    /**
     * @return ActiveQuery
     */
    public function getEventText(): ActiveQuery
    {
        return $this->hasOne(EventText::class, ['id' => 'event_text_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGame(): ActiveQuery
    {
        return $this->hasOne(Game::class, ['id' => 'game_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getNational(): ActiveQuery
    {
        return $this->hasOne(National::class, ['id' => 'national_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPlayer(): ActiveQuery
    {
        return $this->hasOne(Player::class, ['id' => 'player_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }
}
