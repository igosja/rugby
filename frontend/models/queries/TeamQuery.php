<?php

// TODO refactor

namespace frontend\models\queries;

use common\models\db\Team;
use yii\db\ActiveQuery;

/**
 * Class TeamQuery
 * @package frontend\models\queries
 */
class TeamQuery
{
    /**
     * @param int $teamId
     * @return Team|null
     */
    public static function getFreeTeamById(int $teamId): ?Team
    {
        /**
         * @var Team $team
         */
        $team = Team::find()
            ->select(
                [
                    'id',
                ]
            )
            ->where(
                [
                    'id' => $teamId,
                    'user_id' => 0,
                ]
            )
            ->limit(1)
            ->one();
        return $team;
    }

    /**
     * @return ActiveQuery
     */
    public static function getFreeTeamListQuery(): ActiveQuery
    {
        return Team::find()
            ->where(['!=', 'id', 0])
            ->andWhere(['user_id' => 0]);
    }

    /**
     * @param int $teamId
     * @return Team|null
     */
    public static function getTeamById(int $teamId): ?Team
    {
        /**
         * @var Team $team
         */
        $team = Team::find()
            ->where(['id' => $teamId])
            ->limit(1)
            ->one();
        return $team;
    }

    /**
     * @return ActiveQuery
     */
    public static function getTeamGroupByCountryListQuery(): ActiveQuery
    {
        return Team::find()
            ->joinWith(['stadium.city.country'], false)
            ->select(
                [
                    'player_number' => 'COUNT(team.id)',
                    'stadium_id',
                ]
            )
            ->where(['!=', 'team.id', 0])
            ->orderBy(['country.name' => SORT_ASC])
            ->groupBy(['country.id']);
    }

    /**
     * @param int $userId
     * @return array
     */
    public static function getTeamListByUserId(int $userId): array
    {
        return Team::find()
            ->where(
                [
                    'or',
                    ['user_id' => $userId],
                    ['vice_user_id' => $userId],
                ]
            )
            ->andWhere(['!=', 'id', 0])
            ->indexBy('id')
            ->all();
    }

    /**
     * @param int $countryId
     * @return ActiveQuery
     */
    public static function getTeamListQuery(int $countryId): ActiveQuery
    {
        return Team::find()
            ->joinWith(['user', 'stadium.city'], false)
            ->where(['city.country_id' => $countryId]);
    }
}
