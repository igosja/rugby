<?php

namespace backend\models\queries;

use common\models\db\TransferComment;

/**
 * Class TransferCommentQuery
 * @package backend\models\queries
 */
class TransferCommentQuery
{
    /**
     * @return int
     */
    public static function countUnchecked(): int
    {
        return TransferComment::find()
            ->andWhere(['check' => null])
            ->count();
    }
}
