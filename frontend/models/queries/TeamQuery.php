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
                    'baseTraining' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'id',
                                'level',
                            ]
                        );
                    },
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
                                                    'country' => static function (
                                                        ActiveQuery $query
                                                    ) {
                                                        $query->select(
                                                            [
                                                                'id',
                                                                'name',
                                                            ]
                                                        );
                                                    }
                                                ]
                                            )
                                            ->select(
                                                [
                                                    'country_id',
                                                    'id',
                                                    'name',
                                                ]
                                            );
                                    }
                                ]
                            )
                            ->select(
                                [
                                    'capacity',
                                    'city_id',
                                    'id',
                                ]
                            );
                    },
                    'teamRequests' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'team_id',
                            ]
                        );
                    }
                ]
            )
            ->select(
                [
                    'base_id',
                    'base_medical_id',
                    'base_physical_id',
                    'base_school_id',
                    'base_scout_id',
                    'base_training_id',
                    'finance',
                    'team.id',
                    'team.name',
                    'power_vs',
                    'stadium_id',
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
                    'base' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'base_id',
                                'base_level',
                                'base_slot_max',
                            ]
                        );
                    },
                    'baseMedical' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'base_medical_id',
                                'base_medical_level',
                            ]
                        );
                    },
                    'basePhysical' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'base_physical_id',
                                'base_physical_level',
                            ]
                        );
                    },
                    'baseSchool' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'base_school_id',
                                'base_school_level',
                            ]
                        );
                    },
                    'baseScout' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'base_scout_id',
                                'base_scout_level',
                            ]
                        );
                    },
                    'baseTraining' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'base_training_id',
                                'base_training_level',
                            ]
                        );
                    },
                    'buildingBase' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'building_base_team_id',
                            ]
                        );
                    },
                    'buildingStadium' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'building_stadium_team_id',
                            ]
                        );
                    },
                    'championship' => static function (ActiveQuery $query) {
                        $query
                            ->with(
                                [
                                    'country' => static function (
                                        ActiveQuery $query
                                    ) {
                                        $query->select(
                                            [
                                                'country_id',
                                                'country_name',
                                            ]
                                        );
                                    }
                                ]
                            )
                            ->with(
                                [
                                    'division' => static function (
                                        ActiveQuery $query
                                    ) {
                                        $query->select(
                                            [
                                                'division_id',
                                                'division_name',
                                            ]
                                        );
                                    }
                                ]
                            )
                            ->select(
                                [
                                    'championship_country_id',
                                    'championship_division_id',
                                    'championship_place',
                                    'championship_team_id',
                                ]
                            );
                    },
                    'conference' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'conference_place',
                                'conference_team_id',
                            ]
                        );
                    },
                    'manager' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'user_date_vip',
                                'user_id',
                                'user_login',
                                'user_name',
                                'user_surname',
                            ]
                        );
                    },
                    'offSeason' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'off_season_place',
                                'off_season_team_id',
                            ]
                        );
                    },
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
                                                    'country' => static function (
                                                        ActiveQuery $query
                                                    ) {
                                                        $query->select(
                                                            [
                                                                'country_id',
                                                                'country_name',
                                                            ]
                                                        );
                                                    }
                                                ]
                                            )
                                            ->select(
                                                [
                                                    'city_country_id',
                                                    'city_id',
                                                    'city_name',
                                                ]
                                            );
                                    }
                                ]
                            )
                            ->select(
                                [
                                    'stadium_capacity',
                                    'stadium_city_id',
                                    'stadium_id',
                                    'stadium_name',
                                ]
                            );
                    },
                    'vice' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'user_date_vip',
                                'user_id',
                                'user_login',
                                'user_name',
                                'user_surname',
                            ]
                        );
                    },
                ]
            )
            ->select(
                [
                    'team_base_id',
                    'team_base_medical_id',
                    'team_base_physical_id',
                    'team_base_school_id',
                    'team_base_scout_id',
                    'team_base_training_id',
                    'team_finance',
                    'team_id',
                    'team_name',
                    'team_power_s_15',
                    'team_power_s_19',
                    'team_power_s_24',
                    'team_power_vs',
                    'team_price_base',
                    'team_price_total',
                    'team_stadium_id',
                    'team_vice_id',
                    'team_user_id',
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
                                                    'country' => static function (
                                                        ActiveQuery $query
                                                    ) {
                                                        $query->select(
                                                            [
                                                                'country_id',
                                                                'country_name',
                                                            ]
                                                        );
                                                    }
                                                ]
                                            )
                                            ->select(
                                                [
                                                    'city_country_id',
                                                    'city_id',
                                                ]
                                            );
                                    }
                                ]
                            )
                            ->select(
                                [
                                    'stadium_city_id',
                                    'stadium_id',
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
            ->select(
                [
                    'base_scout_id',
                    'id',
                    'name',
                    'stadium_id',
                    'user_id',
                    'vice_user_id',
                ]
            )
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
                                    'city' => static function (
                                        ActiveQuery $query
                                    ) {
                                        $query->select(
                                            [
                                                'city_country_id',
                                                'city_id',
                                                'city_name',
                                            ]
                                        );
                                    },
                                ]
                            )
                            ->select(
                                [
                                    'stadium_city_id',
                                    'stadium_id',
                                ]
                            );
                    },
                    'manager' => static function (ActiveQuery $query) {
                        $query->select(
                            [
                                'user_date_login',
                                'user_date_vip',
                                'user_id',
                                'user_login',
                            ]
                        );
                    },
                ]
            )
            ->select(
                [
                    'team_id',
                    'team_name',
                    'team_stadium_id',
                    'team_user_id',
                ]
            )
            ->where(['city_country_id' => $countryId]);
    }
}
