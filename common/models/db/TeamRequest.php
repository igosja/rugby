<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * Class TeamRequest
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $leave_team_id
 * @property int $team_id
 * @property int $user_id
 *
 * @property-read Team $leaveTeam
 * @property-read Recommendation $recommendation
 * @property-read Team $team
 * @property-read User $user
 */
class TeamRequest extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%team_request}}';
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['team_id', 'user_id'], 'required'],
            [['leave_team_id', 'team_id', 'user_id'], 'integer', 'min' => 1],
            [['leave_team_id'], 'exist', 'targetRelation' => 'leaveTeam'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getLeaveTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'leave_team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRecommendation(): ActiveQuery
    {
        return $this->hasOne(Recommendation::class, ['team_id' => 'team_id', 'user_id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
