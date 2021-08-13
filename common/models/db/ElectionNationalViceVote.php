<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * Class ElectionNationalViceVote
 * @package common\models\db
 *
 * @property int $id
 * @property int $election_national_vice_application_id
 * @property int $date
 * @property int $user_id
 *
 * @property-read ElectionNationalViceApplication $electionNationalViceApplication
 * @property-read User $user
 */
class ElectionNationalViceVote extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_national_vice_vote}}';
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
            [['election_national_vice_application_id', 'user_id'], 'required'],
            [['election_national_vice_application_id', 'user_id'], 'integer', 'min' => 1],
            [['election_national_vice_application_id'], 'exist', 'targetRelation' => 'electionNationalViceApplication'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionNationalViceApplication(): ActiveQuery
    {
        return $this->hasOne(ElectionNationalViceApplication::class, ['id' => 'election_national_vice_application_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
