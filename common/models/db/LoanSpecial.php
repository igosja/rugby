<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class LoanSpecial
 * @package common\models\db
 *
 * @property int $id
 * @property int $level
 * @property int $loan_id
 * @property int $special_id
 *
 * @property-read Loan $loan
 * @property-read Special $special
 */
class LoanSpecial extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%loan_special}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['level', 'loan_id', 'special_id'], 'required'],
            [['level'], 'integer', 'min' => 1, 'max' => 4],
            [['special_id'], 'integer', 'min' => 1, 'max' => 99],
            [['loan_id'], 'integer', 'min' => 1],
            [['loan_id'], 'exist', 'targetRelation' => 'loan'],
            [['special_id'], 'exist', 'targetRelation' => 'special'],
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
    public function getSpecial(): ActiveQuery
    {
        return $this->hasOne(Special::class, ['id' => 'special_id']);
    }
}
