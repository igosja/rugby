<?php

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
            ->select([
                'achievement_id',
            ])
            ->where(['achievement_team_id' => $teamId])
            ->orderBy(['achievement_id' => SORT_DESC]);
    }

    /**
     * @param int $teamId
     * @return ActiveQuery
     */
    public static function getTeamTrophyListQuery(int $teamId): ActiveQuery
    {
        return Achievement::find()
            ->select([
                'achievement_id',
            ])
            ->where([
                'achievement_team_id' => $teamId,
                'achievement_place' => [0, 1],
                'achievement_stage_id' => 0,
            ])
            ->orderBy(['achievement_id' => SORT_DESC]);
    }
}
