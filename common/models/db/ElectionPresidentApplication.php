<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class ElectionPresidentApplication
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $election_president_id
 * @property string $text
 * @property int $user_id
 *
 * @property-read ElectionPresident $electionPresident
 * @property-read User $user
 */
class ElectionPresidentApplication extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_president_application}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['election_president_id', 'text', 'user_id'], 'required'],
            [['election_president_id', 'user_id'], 'integer', 'min' => 1],
            [['text'], 'trim'],
            [['text'], 'string'],
            [['election_president_id'], 'exist', 'targetRelation' => 'electionPresident'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionPresident(): ActiveQuery
    {
        return $this->hasOne(ElectionPresident::class, ['id' => 'election_president_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
