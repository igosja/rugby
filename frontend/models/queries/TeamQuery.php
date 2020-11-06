<?php

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
            ->joinWith(['stadium.city'], false)
            ->with(
                [
                    'base' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'id',
                                'slot_max',
                            ]
                        );
                    },
                    'baseMedical' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'id',
                                'level',
                            ]
                        );
                    },
                    'basePhysical' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'id',
                                'level',
                            ]
                        );
                    },
                    'baseSchool' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'id',
                                'level',
                            ]
                        );
                    },
                    'baseScout' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'id',
                                'level',
                            ]
                        );
                    },
                    'baseTraining',
                    'stadium' => static function (ActiveQuery $query) {
                        $query
                            ->with(
                                [
                                    'city' => static function (
                                        ActiveQuery $query
                                    ) {
                                        $query
                                            ->with(
                                                [
                                                    'country',
                                                ]
                                            );
                                    }
                                ]
                            );
                    },
                    'teamRequests'
                ]
            )
            ->where(['!=', 'team.id', 0])
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
            ->with(
                [
                    'base',
                    'baseMedical',
                    'basePhysical',
                    'baseSchool',
                    'baseScout',
                    'baseTraining',
                    'buildingBase',
                    'buildingStadium',
                    'championship' => static function (ActiveQuery $query) {
                        $query
                            ->with(
                                [
                                    'country',
                                    'division',
                                ]
                            );
                    },
                    'conference',
                    'manager',
                    'offSeason',
                    'stadium' => static function (ActiveQuery $query) {
                        $query
                            ->with(
                                [
                                    'city' => static function (
                                        ActiveQuery $query
                                    ) {
                                        $query
                                            ->with(
                                                [
                                                    'country',
                                                ]
                                            );
                                    }
                                ]
                            );
                    },
                    'vice',
                ]
            )
            ->where(['team_id' => $teamId])
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
            ->with(
                [
                    'stadium' => static function (ActiveQuery $query) {
                        $query
                            ->with(
                                [
                                    'city' => static function (
                                        ActiveQuery $query
                                    ) {
                                        $query
                                            ->with(
                                                [
                                                    'country',
                                                ]
                                            );
                                    }
                                ]
                            );
                    }
                ]
            )
            ->joinWith(['stadium.city.country'], false)
            ->select(
                [
                    'team_player' => 'COUNT(team_id)',
                    'team_stadium_id',
                ]
            )
            ->where(['!=', 'team_id', 0])
            ->orderBy(['country_name' => SORT_ASC])
            ->groupBy(['country_id']);
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
            ->all();
    }

    /**
     * @param int $countryId
     * @return ActiveQuery
     */
    public static function getTeamListQuery(int $countryId): ActiveQuery
    {
        return Team::find()
            ->joinWith(
                [
                    'manager',
                    'stadium.city'
                ],
                false
            )
            ->with(
                [
                    'stadium' => static function (ActiveQuery $query) {
                        $query
                            ->with(
                                [
                                    'city',
                                ]
                            );
                    },
                    'manager',
                ]
            )
            ->where(['city_country_id' => $countryId]);
    }
}
