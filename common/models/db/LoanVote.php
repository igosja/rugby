<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class LoanVote
 * @package common\models\db
 *
 * @property int $loan_vote_id
 * @property int $loan_vote_loan_id
 * @property int $loan_vote_rating
 * @property int $loan_vote_user_id
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
}
