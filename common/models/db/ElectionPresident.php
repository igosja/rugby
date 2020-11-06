<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class ElectionPresident
 * @package common\models\db
 *
 * @property int $id
 * @property int $federation_id
 * @property int $date
 * @property int $election_status_id
 *
 * @property-read Federation $federation
 * @property-read ElectionStatus $electionStatus
 */
class ElectionPresident extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_president}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['federation_id', 'election_status_id'], 'required'],
            [['federation_id'], 'integer', 'min' => 0, 'max' => 999],
            [['election_status_id'], 'integer', 'min' => 0, 'max' => 9],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
            [['election_status_id'], 'exist', 'targetRelation' => 'electionStatus'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getFederation(): ActiveQuery
    {
        return $this->hasOne(Federation::class, ['id' => 'federation_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionStatus(): ActiveQuery
    {
        return $this->hasOne(ElectionStatus::class, ['id' => 'election_status_id']);
    }
}
