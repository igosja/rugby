<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class ElectionPresidentViceApplication
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $election_president_vice_id
 * @property string $text
 * @property int $user_id
 *
 * @property-read ElectionPresidentVice $electionPresidentVice
 * @property-read ElectionPresidentViceVote[] $electionPresidentViceVotes
 * @property-read User $user
 */
class ElectionPresidentViceApplication extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_president_vice_application}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['election_president_vice_id', 'text', 'user_id'], 'required'],
            [['election_president_vice_id', 'user_id'], 'integer', 'min' => 1],
            [['text'], 'trim'],
            [['text'], 'string'],
            [['election_president_vice_id'], 'exist', 'targetRelation' => 'electionPresidentVice'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionPresidentVice(): ActiveQuery
    {
        return $this->hasOne(ElectionPresidentVice::class, ['id' => 'election_president_vice_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionPresidentViceVotes(): ActiveQuery
    {
        return $this->hasMany(ElectionPresidentViceVote::class, ['election_president_vice_application_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
