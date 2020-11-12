<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class LoanApplication
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $day
 * @property int $deal_reason_id
 * @property bool $is_only_one
 * @property int $loan_id
 * @property int $price
 * @property int $team_id
 * @property int $user_id
 *
 * @property-read DealReason $dealReason
 * @property-read Loan $loan
 * @property-read Team $team
 * @property-read User $user
 */
class LoanApplication extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%loan_application}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['day', 'is_only_one', 'loan_id', 'price', 'team_id', 'user_id'], 'required'],
            [['is_only_one'], 'boolean'],
            [['day'], 'integer', 'min' => 1, 'max' => 9],
            [['deal_reason_id'], 'integer', 'min' => 1, 'max' => 99],
            [['loan_id', 'price', 'team_id', 'user_id'], 'integer', 'min' => 1],
            [['deal_reason_id'], 'exist', 'targetRelation' => 'dealReason'],
            [['loan_id'], 'exist', 'targetRelation' => 'loan'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getDealReason(): ActiveQuery
    {
        return $this->hasOne(DealReason::class, ['id' => 'deal_reason_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLoan(): ActiveQuery
    {
        return $this->hasOne(Loan::class, ['id' => 'loan_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
