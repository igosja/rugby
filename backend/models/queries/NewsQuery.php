<?php

namespace backend\models\queries;

use common\models\db\News;

/**
 * Class NewsQuery
 * @package backend\models\queries
 */
class NewsQuery
{
    /**
     * @return int
     */
    public static function countUnchecked(): int
    {
        return News::find()
            ->andWhere(['check' => null])
            ->count();
    }
}
