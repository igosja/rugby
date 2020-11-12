<?php

// TODO refactor

namespace frontend\models\preparers;

use frontend\models\queries\AchievementQuery;
use yii\data\ActiveDataProvider;

/**
 * Class AchievementPrepare
 * @package frontend\models\preparers
 */
class AchievementPrepare
{
    /**
     * @param int $teamId
     * @return ActiveDataProvider
     */
    public static function getTeamAchievementDataProvider(int $teamId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'pagination' => false,
            'query' => AchievementQuery::getTeamAchievementListQuery($teamId),
        ]);
    }

    /**
     * @param int $teamId
     * @return ActiveDataProvider
     */
    public static function getTeamTrophyDataProvider(int $teamId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'pagination' => false,
            'query' => AchievementQuery::getTeamTrophyListQuery($teamId),
        ]);
    }
}