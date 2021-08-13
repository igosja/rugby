<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class LoanVote
 * @package common\models\db
 *
 * @property int $id
 * @property int $loan_id
 * @property int $rating
 * @property int $user_id
 *
 * @property-read Loan $loan
 * @property-read User $user
 */
class LoanVote extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%loan_vote}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['loan_id', 'rating', 'user_id'], 'required'],
            [['rating'], 'integer', 'min' => -10, 'max' => 10],
            [['loan_id', 'user_id'], 'integer', 'min' => 1],
            [['loan_id'], 'exist', 'targetRelation' => 'loan'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
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
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
