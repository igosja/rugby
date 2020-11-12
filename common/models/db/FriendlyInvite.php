<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class FriendlyInvite
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $friendly_invite_status_id
 * @property int $guest_team_id
 * @property int $guest_user_id
 * @property int $home_team_id
 * @property int $home_user_id
 * @property int $schedule_id
 *
 * @property-read FriendlyInviteStatus $friendlyInviteStatus
 * @property-read Team $guestTeam
 * @property-read User $guestUser
 * @property-read Team $homeTeam
 * @property-read User $homeUser
 * @property-read Schedule $schedule
 */
class FriendlyInvite extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%friendly_invite}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [
                [
                    'friendly_invite_status_id',
                    'guest_team_id',
                    'guest_user_id',
                    'home_team_id',
                    'home_user_id',
                    'schedule_id'
                ],
                'required'
            ],
            [['friendly_invite_status_id'], 'integer', 'min' => 0, 'max' => 9],
            [['guest_team_id', 'guest_user_id', 'home_team_id', 'home_user_id', 'schedule_id'], 'integer', 'min' => 0],
            [['friendly_invite_status_id'], 'exist', 'targetRelation' => 'friendlyInviteStatus'],
            [['guest_team_id'], 'exist', 'targetRelation' => 'guestTeam'],
            [['guest_user_id'], 'exist', 'targetRelation' => 'guestUser'],
            [['home_team_id'], 'exist', 'targetRelation' => 'homeTeam'],
            [['home_user_id'], 'exist', 'targetRelation' => 'homeUser'],
            [['schedule_id'], 'exist', 'targetRelation' => 'schedule'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getFriendlyInviteStatus(): ActiveQuery
    {
        return $this->hasOne(FriendlyInviteStatus::class, ['id' => 'friendly_invite_status_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGuestTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'guest_team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGuestUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'guest_user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHomeTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'home_team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHomeUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'home_user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSchedule(): ActiveQuery
    {
        return $this->hasOne(Schedule::class, ['id' => 'schedule_id']);
    }
}
