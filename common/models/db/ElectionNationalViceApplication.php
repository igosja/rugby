<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * Class ElectionNationalViceApplication
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $election_national_vice_id
 * @property string $text
 * @property int $user_id
 *
 * @property-read ElectionNationalVice $electionNationalVice
 * @property-read ElectionNationalViceVote[] $electionNationalViceVotes
 * @property-read User $user
 */
class ElectionNationalViceApplication extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_national_vice_application}}';
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
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['election_national_vice_id', 'text', 'user_id'], 'required'],
            [['election_national_vice_id', 'user_id'], 'integer', 'min' => 0],
            [['text'], 'trim'],
            [['text'], 'string'],
            [['election_national_vice_id'], 'exist', 'targetRelation' => 'electionNationalVice'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionNationalVice(): ActiveQuery
    {
        return $this->hasOne(ElectionNationalVice::class, ['id' => 'election_national_vice_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionNationalViceVotes(): ActiveQuery
    {
        return $this->hasMany(ElectionNationalViceVote::class, ['election_national_vice_application_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
