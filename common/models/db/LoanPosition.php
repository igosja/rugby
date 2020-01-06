<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class LoanPosition
 * @package common\models\db
 *
 * @property int $loan_position_id
 * @property int $loan_position_loan_id
 * @property int $loan_position_position_id
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
}
