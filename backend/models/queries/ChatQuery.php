<?php

namespace backend\models\queries;

use common\models\db\Chat;

/**
 * Class ChatQuery
 * @package backend\models\queries
 */
class ChatQuery
{
    /**
     * @return int
     */
    public static function countUnchecked(): int
    {
        return Chat::find()
            ->andWhere(['check' => null])
            ->count();
    }
}
