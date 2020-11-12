<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class LoanPosition
 * @package common\models\db
 *
 * @property int $id
 * @property int $loan_id
 * @property int $position_id
 *
 * @property-read Loan $loan
 * @property-read Position $position
 */
class LoanPosition extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%loan_position}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['loan_id', 'position_id'], 'required'],
            [['position_id'], 'integer', 'min' => 1, 'max' => 99],
            [['loan_id'], 'integer', 'min' => 1],
            [['loan_id'], 'exist', 'targetRelation' => 'loan'],
            [['position_id'], 'exist', 'targetRelation' => 'position'],
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
    public function getPosition(): ActiveQuery
    {
        return $this->hasOne(Position::class, ['id' => 'position_id']);
    }
}
