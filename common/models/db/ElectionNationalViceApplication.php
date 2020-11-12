<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
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
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['election_national_vice_id', 'text', 'user_id'], 'required'],
            [['election_national_vice_id', 'user_id'], 'integer', 'min' => 1],
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
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
