<?php

namespace frontend\models\queries;

use common\models\db\Game;
use yii\db\ActiveQuery;

/**
 * Class GameQuery
 * @package frontend\models\queries
 */
class GameQuery
{
    /**
     * @param int $scheduleId
     * @return ActiveQuery
     */
    public static function getGameListQuery(int $scheduleId): ActiveQuery
    {
        return Game::find()
            ->where(['schedule_id' => $scheduleId])
            ->orderBy(['id' => SORT_DESC]);
    }

    /**
     * @param int $teamId
     * @return Game[]
     */
    public static function getLastThreeGames(int $teamId): array
    {
        return Game::find()
            ->joinWith(['schedule'], false)
            ->where(['or', ['home_team_id' => $teamId], ['guest_team_id' => $teamId]])
            ->andWhere(['!=', 'played', 0])
            ->orderBy(['schedule_date' => SORT_DESC])
            ->limit(3)
            ->all();
    }

    /**
     * @param int $teamId
     * @return Game[]
     */
    public static function getNearestTwoGames(int $teamId): array
    {
        return Game::find()
            ->joinWith(['schedule'], false)
            ->where(['or', ['home_team_id' => $teamId], ['guest_team_id' => $teamId]])
            ->andWhere(['played' => 0])
            ->orderBy(['schedule_date' => SORT_ASC])
            ->limit(2)
            ->all();
    }

    /**
     * @param int $teamId
     * @param int $seasonId
     * @return ActiveQuery
     */
    public static function getTeamGameListQuery(int $teamId, int $seasonId): ActiveQuery
    {
        return Game::find()
            ->joinWith(['schedule'], false)
            ->where(['or', ['home_team_id' => $teamId], ['guest_team_id' => $teamId]])
            ->andWhere(['schedule_season_id' => $seasonId])
            ->orderBy(['schedule_date' => SORT_ASC]);
    }
}
