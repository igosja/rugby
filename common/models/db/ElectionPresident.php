<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
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
 * @property-read ElectionPresidentApplication[] $electionPresidentApplications
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
            [['federation_id', 'election_status_id'], 'required'],
            [['federation_id'], 'integer', 'min' => 0, 'max' => 999],
            [['election_status_id'], 'integer', 'min' => 0, 'max' => 9],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
            [['election_status_id'], 'exist', 'targetRelation' => 'electionStatus'],
        ];
    }

    /**
     * @return array
     */
    public function applications(): array
    {
        $result = [];
        $total = 0;
        foreach ($this->electionPresidentApplications as $electionPresidentApplication) {
            $count = count($electionPresidentApplication->electionPresidentVotes);
            $result[] = [
                'count' => $count,
                'user' => $electionPresidentApplication->user_id ? $electionPresidentApplication->user->getUserLink() : Yii::t('common', 'models.db.election.application.against'),
                'logo' => $electionPresidentApplication->user_id ? $electionPresidentApplication->user->smallLogo() : '',
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
    public function getFederation(): ActiveQuery
    {
        return $this->hasOne(Federation::class, ['id' => 'federation_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionPresidentApplications(): ActiveQuery
    {
        return $this->hasMany(ElectionPresidentApplication::class, ['election_president_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionStatus(): ActiveQuery
    {
        return $this->hasOne(ElectionStatus::class, ['id' => 'election_status_id']);
    }
}
