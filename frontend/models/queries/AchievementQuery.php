<?php

// TODO refactor

namespace frontend\models\queries;

use common\models\db\Achievement;
use yii\db\ActiveQuery;

/**
 * Class AchievementQuery
 * @package frontend\models\queries
 */
class AchievementQuery
{
    /**
     * @param int $teamId
     * @return ActiveQuery
     */
    public static function getTeamAchievementListQuery(int $teamId): ActiveQuery
    {
        return Achievement::find()
            ->where(['team_id' => $teamId])
            ->orderBy(['id' => SORT_DESC]);
    }

    /**
     * @param int $teamId
     * @return ActiveQuery
     */
    public static function getTeamTrophyListQuery(int $teamId): ActiveQuery
    {
        return Achievement::find()
            ->where([
                'team_id' => $teamId,
                'place' => [null, 1],
                'stage_id' => null,
            ])
            ->orderBy(['id' => SORT_DESC]);
    }
}
