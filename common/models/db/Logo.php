<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Logo
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $team_id
 * @property string $text
 * @property int $user_id
 *
 * @property-read Team $team
 * @property-read User $user
 */
class Logo extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%logo}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['team_id', 'text', 'user_id'], 'required'],
            [['text'], 'string'],
            [['team_id', 'user_id'], 'integer', 'min' => 1],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
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
