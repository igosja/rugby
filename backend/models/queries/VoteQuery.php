<?php

namespace backend\models\queries;

use common\models\db\Vote;
use common\models\db\VoteStatus;

/**
 * Class VoteQuery
 * @package backend\models\queries
 */
class VoteQuery
{
    /**
     * @return int
     */
    public static function countNew(): int
    {
        return Vote::find()
            ->andWhere(['vote_status_id' => VoteStatus::NEW])
            ->count();
    }
}
