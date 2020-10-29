<?php

namespace backend\models\queries;

use common\models\db\LoanComment;

/**
 * Class LoanCommentQuery
 * @package backend\models\queries
 */
class LoanCommentQuery
{
    /**
     * @return int
     */
    public static function countUnchecked(): int
    {
        return LoanComment::find()
            ->andWhere(['check' => null])
            ->count();
    }
}
