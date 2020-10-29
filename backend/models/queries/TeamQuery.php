<?php

namespace backend\models\queries;

use common\models\db\Team;

/**
 * Class TeamQuery
 * @package backend\models\queries
 */
class TeamQuery
{
    /**
     * @return int
     */
    public static function countFreeTeam(): int
    {
        return Team::find()
            ->andWhere(['user_id' => 0])
            ->andWhere(['!=', 'id', 0])
            ->count();
    }
}
