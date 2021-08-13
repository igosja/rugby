<?php

// TODO refactor

namespace backend\models\queries;

use common\models\db\ForumMessage;

/**
 * Class ForumMessageQuery
 * @package backend\models\queries
 */
class ForumMessageQuery
{
    /**
     * @return int
     */
    public static function countUnchecked(): int
    {
        return ForumMessage::find()
            ->andWhere(['check' => null])
            ->count();
    }
}
