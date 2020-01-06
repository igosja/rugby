<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class TeamRequest
 * @package common\models\db
 *
 * @property int $team_request_id
 * @property int $team_request_date
 * @property int $team_request_leave_id
 * @property int $team_request_team_id
 * @property int $team_request_user_id
 *
 * @property Recommendation $recommendation
 * @property Team $team
 * @property User $user
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
    public function rules(): array
    {
        return [
            [['team_request_id', 'team_request_date', 'team_request_leave_id', 'team_request_team_id', 'team_request_user_id'], 'integer'],
            [['team_request_team_id', 'team_request_user_id'], 'required'],
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($this->isNewRecord) {
            $this->team_request_date = time();
        }
        return true;
    }

    /**
     * @return ActiveQuery
     */
    public function getRecommendation(): ActiveQuery
    {
        return $this->hasOne(
            Recommendation::class,
            ['recommendation_team_id' => 'team_request_team_id', 'recommendation_user_id' => 'team_request_user_id']
        );
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['team_id' => 'team_request_team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['user_id' => 'team_request_user_id']);
    }
}
