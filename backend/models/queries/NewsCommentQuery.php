<?php

namespace backend\models\queries;

use common\models\db\NewsComment;

/**
 * Class NewsCommentQuery
 * @package backend\models\queries
 */
class NewsCommentQuery
{
    /**
     * @return int
     */
    public static function countUnchecked(): int
    {
        return NewsComment::find()
            ->andWhere(['check' => null])
            ->count();
    }
}
