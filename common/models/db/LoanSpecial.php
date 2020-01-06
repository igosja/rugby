<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class LoanSpecial
 * @package common\models\db
 *
 * @property int $loan_special_id
 * @property int $loan_special_level
 * @property int $loan_special_loan_id
 * @property int $loan_special_special_id
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
}
