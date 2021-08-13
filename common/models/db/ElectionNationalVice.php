<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * Class ElectionNationalVice
 * @package common\models\db
 *
 * @property int $id
 * @property int $federation_id
 * @property int $date
 * @property int $election_status_id
 * @property int $national_type_id
 *
 * @property-read ElectionNationalViceApplication[] $electionNationalViceApplications
 * @property-read ElectionStatus $electionStatus
 * @property-read Federation $federation
 * @property-read National $national
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
            [['federation_id', 'election_status_id', 'national_type_id'], 'required'],
            [['federation_id'], 'integer', 'min' => 0, 'max' => 999],
            [['election_status_id', 'national_type_id'], 'integer', 'min' => 0, 'max' => 9],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
            [['election_status_id'], 'exist', 'targetRelation' => 'electionStatus'],
            [['national_type_id'], 'exist', 'targetRelation' => 'nationalType'],
        ];
    }

    /**
     * @return array
     */
    public function applications(): array
    {
        $result = [];
        $total = 0;
        foreach ($this->electionNationalViceApplications as $electionNationalViceApplication) {
            $count = count($electionNationalViceApplication->electionNationalViceVotes);
            $result[] = [
                'count' => $count,
                'user' => $electionNationalViceApplication->user_id ? $electionNationalViceApplication->user->getUserLink() : Yii::t('common', 'models.db.election.application.against'),
                'logo' => $electionNationalViceApplication->user_id ? $electionNationalViceApplication->user->smallLogo() : '',
            ];
            $total += $count;
        }
        foreach ($result as $key => $value) {
            $result[$key]['percent'] = $total ? round($result[$key]['count'] / $total * 100) : 0;
        }
        usort($result, static function ($a, $b) {
            return $b['count'] > $a['count'] ? 1 : 0;
        });
        return $result;
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionNationalViceApplications(): ActiveQuery
    {
        return $this->hasMany(ElectionNationalViceApplication::class, ['election_national_vice_id' => 'id']);
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
