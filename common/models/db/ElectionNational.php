<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class ElectionNational
 * @package common\models\db
 *
 * @property int $id
 * @property int $federation_id
 * @property int $date
 * @property int $election_status_id
 * @property int $national_type_id
 *
 * @property-read Federation $federation
 * @property-read ElectionNationalApplication[] $electionNationalApplications
 * @property-read ElectionStatus $electionStatus
 * @property-read National $national
 * @property-read NationalType $nationalType
 */
class ElectionNational extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_national}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['federation_id', 'election_status_id', 'national_type_id'], 'required'],
            [['federation_id'], 'integer', 'min' => 0, 'max' => 999],
            [['election_status_id', 'national_type_id'], 'integer', 'min' => 0, 'max' => 9],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
            [['election_status_id'], 'exist', 'targetRelation' => 'electionStatus'],
            [['national_type_id'], 'exist', 'targetRelation' => 'nationalType'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionNationalApplications(): ActiveQuery
    {
        return $this->hasMany(ElectionNationalApplication::class, ['election_national_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionStatus(): ActiveQuery
    {
        return $this->hasOne(ElectionStatus::class, ['id' => 'election_status_id']);
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
    public function getNational(): ActiveQuery
    {
        return $this->hasOne(
            National::class,
            ['national_type_id' => 'national_type_id', 'federation_id' => 'federation_id']
        );
    }

    /**
     * @return ActiveQuery
     */
    public function getNationalType(): ActiveQuery
    {
        return $this->hasOne(NationalType::class, ['id' => 'national_type_id']);
    }
}
