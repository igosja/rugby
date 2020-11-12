<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class ElectionNationalApplication
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $election_national_id
 * @property string $text
 * @property int $user_id
 *
 * @property-read ElectionNational $electionNational
 * @property-read User $user
 */
class ElectionNationalApplication extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_national_application}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['election_national_id', 'text', 'user_id'], 'required'],
            [['election_national_id', 'user_id'], 'integer', 'min' => 1],
            [['text'], 'trim'],
            [['text'], 'string'],
            [['election_national_id'], 'exist', 'targetRelation' => 'electionNational'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionNational(): ActiveQuery
    {
        return $this->hasOne(ElectionNational::class, ['id' => 'election_national_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
