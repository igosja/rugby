<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class ElectionPresidentVote
 * @package common\models\db
 *
 * @property int $id
 * @property int $election_president_application_id
 * @property int $date
 * @property int $user_id
 *
 * @property-read ElectionPresidentApplication $electionPresidentApplication
 * @property-read User $user
 */
class ElectionPresidentVote extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_president_vote}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['election_president_application_id', 'user_id'], 'required'],
            [['election_president_application_id', 'user_id'], 'integer', 'min' => 1],
            [['election_president_application_id'], 'exist', 'targetRelation' => 'electionPresidentApplication'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionPresidentApplication(): ActiveQuery
    {
        return $this->hasOne(ElectionPresidentApplication::class, ['id' => 'election_president_application_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
