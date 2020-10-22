<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class ElectionNationalVice
 * @package common\models\db
 *
 * @property int $id
 * @property int $country_id
 * @property int $date
 * @property int $election_status_id
 * @property int $national_type_id
 *
 * @property-read Country $country
 * @property-read ElectionStatus $electionStatus
 * @property-read NationalType $nationalType
 */
class ElectionNationalVice extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_national_vice}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['country_id', 'election_status_id', 'national_type_id'], 'required'],
            [['country_id'], 'integer', 'min' => 0, 'max' => 999],
            [['election_status_id', 'national_type_id'], 'integer', 'min' => 0, 'max' => 9],
            [['country_id'], 'exist', 'targetRelation' => 'country'],
            [['election_status_id'], 'exist', 'targetRelation' => 'electionStatus'],
            [['national_type_id'], 'exist', 'targetRelation' => 'nationalType'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
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
    public function getNationalType(): ActiveQuery
    {
        return $this->hasOne(NationalType::class, ['id' => 'national_type_id']);
    }
}
