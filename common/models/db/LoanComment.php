<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class LoanComment
 * @package common\models\db
 *
 * @property int $loan_comment_id
 * @property int $loan_comment_check
 * @property int $loan_comment_date
 * @property int $loan_comment_loan_id
 * @property string $loan_comment_text
 * @property int $loan_comment_user_id
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
}
