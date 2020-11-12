<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class ElectionPresidentViceVote
 * @package common\models\db
 *
 * @property int $id
 * @property int $election_president_vice_application_id
 * @property int $date
 * @property int $user_id
 *
 * @property-read ElectionPresidentViceApplication $electionPresidentViceApplication
 * @property-read User $user
 */
class ElectionPresidentViceVote extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_president_vice_vote}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['election_president_vice_application_id', 'user_id'], 'required'],
            [['election_president_vice_application_id', 'user_id'], 'integer', 'min' => 1],
            [
                ['election_president_vice_application_id'],
                'exist',
                'targetRelation' => 'electionPresidentViceApplication'
            ],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionPresidentViceApplication(): ActiveQuery
    {
        return $this->hasOne(
            ElectionPresidentViceApplication::class,
            ['id' => 'election_president_vice_application_id']
        );
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
