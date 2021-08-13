<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * Class ElectionNationalVote
 * @package common\models\db
 *
 * @property int $id
 * @property int $election_national_application_id
 * @property int $date
 * @property int $user_id
 *
 * @property-read ElectionNationalApplication $electionNationalApplication
 * @property-read User $user
 */
class ElectionNationalVote extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_national_vote}}';
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
            [['election_national_application_id', 'user_id'], 'required'],
            [['election_national_application_id', 'user_id'], 'integer', 'min' => 1],
            [['election_national_application_id'], 'exist', 'targetRelation' => 'electionNationalApplication'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionNationalApplication(): ActiveQuery
    {
        return $this->hasOne(ElectionNationalApplication::class, ['id' => 'election_national_application_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
