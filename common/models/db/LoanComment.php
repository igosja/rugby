<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class LoanComment
 * @package common\models\db
 *
 * @property int $id
 * @property int $check
 * @property int $date
 * @property int $loan_id
 * @property string $text
 * @property int $user_id
 *
 * @property-read Loan $loan
 * @property-read User $user
 */
class LoanComment extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%loan_comment}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['loan_id', 'text', 'user_id'], 'required'],
            [['text'], 'string'],
            [['check', 'loan_id', 'user_id'], 'integer', 'min' => 1],
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
