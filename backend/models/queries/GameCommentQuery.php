<?php

namespace backend\models\queries;

use common\models\db\GameComment;

/**
 * Class GameCommentQuery
 * @package backend\models\queries
 */
class GameCommentQuery
{
    /**
     * @return int
     */
    public static function countUnchecked(): int
    {
        return GameComment::find()
            ->andWhere(['check' => null])
            ->count();
    }
}
