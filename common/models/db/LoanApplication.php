<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class LoanApplication
 * @package common\models\db
 *
 * @property int $loan_application_id
 * @property int $loan_application_date
 * @property int $loan_application_day
 * @property int $loan_application_deal_reason_id
 * @property int $loan_application_loan_id
 * @property int $loan_application_only_one
 * @property int $loan_application_price
 * @property int $loan_application_team_id
 * @property int $loan_application_user_id
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
}
