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
            ->with([
                'nationalHome' => static function (ActiveQuery $query) {
                    self::withNationalQuery($query);
                },
                'nationalGuest' => static function (ActiveQuery $query) {
                    self::withNationalQuery($query);
                },
                'teamGuest' => static function (ActiveQuery $query) {
                    self::withTeamQuery($query);
                },
                'teamHome' => static function (ActiveQuery $query) {
                    self::withTeamQuery($query);
                },
            ])
            ->select([
                'game_id',
                'game_guest_auto',
                'game_guest_national_id',
                'game_guest_team_id',
                'game_home_auto',
                'game_home_national_id',
                'game_home_team_id',
            ])
            ->where(['game_schedule_id' => $scheduleId])
            ->orderBy(['game_id' => SORT_DESC]);
    }

    /**
     * @param int $teamId
     * @return Game[]
     */
    public static function getLastThreeGames(int $teamId): array
    {
        return Game::find()
            ->joinWith(['schedule'], false)
            ->with([
                'teamGuest' => static function (ActiveQuery $query) {
                    $query->select([
                        'team_id',
                        'team_name',
                    ]);
                },
                'teamHome' => static function (ActiveQuery $query) {
                    $query->select([
                        'team_id',
                        'team_name',
                    ]);
                },
                'schedule' => static function (ActiveQuery $query) {
                    $query
                        ->with([
                            'tournamentType' => static function (ActiveQuery $query) {
                                $query->select([
                                    'tournament_type_id',
                                    'tournament_type_name',
                                ]);
                            }
                        ])
                        ->select([
                            'schedule_date',
                            'schedule_id',
                            'schedule_tournament_type_id',
                        ]);
                },
            ])
            ->select([
                'game_guest_team_id',
                'game_home_team_id',
                'game_id',
                'game_schedule_id',
            ])
            ->where(['or', ['game_home_team_id' => $teamId], ['game_guest_team_id' => $teamId]])
            ->andWhere(['!=', 'game_played', 0])
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
            ->with([
                'teamGuest' => static function (ActiveQuery $query) {
                    $query->select([
                        'team_id',
                        'team_name',
                    ]);
                },
                'teamHome' => static function (ActiveQuery $query) {
                    $query->select([
                        'team_id',
                        'team_name',
                    ]);
                },
                'schedule' => static function (ActiveQuery $query) {
                    $query
                        ->with([
                            'tournamentType' => static function (ActiveQuery $query) {
                                $query->select([
                                    'tournament_type_id',
                                    'tournament_type_name',
                                ]);
                            }
                        ])
                        ->select([
                            'schedule_date',
                            'schedule_id',
                            'schedule_tournament_type_id',
                        ]);
                },
            ])
            ->select([
                'game_guest_tactic_id',
                'game_guest_team_id',
                'game_home_tactic_id',
                'game_home_team_id',
                'game_id',
                'game_schedule_id',
            ])
            ->where(['or', ['game_home_team_id' => $teamId], ['game_guest_team_id' => $teamId]])
            ->andWhere(['game_played' => 0])
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
            ->with([
                'schedule' => static function (ActiveQuery $query) {
                    $query
                        ->with([
                            'stage' => static function (ActiveQuery $query) {
                                $query->select([
                                    'stage_id',
                                    'stage_name',
                                ]);
                            },
                            'tournamentType' => static function (ActiveQuery $query) {
                                $query->select([
                                    'tournament_type_id',
                                    'tournament_type_name',
                                ]);
                            },
                        ])
                        ->select([
                            'schedule_date',
                            'schedule_id',
                            'schedule_stage_id',
                            'schedule_tournament_type_id',
                        ]);
                },
                'teamHome' => static function (ActiveQuery $query) {
                    self::withTeamQuery($query);
                },
                'teamGuest' => static function (ActiveQuery $query) {
                    self::withTeamQuery($query);
                },
            ])
            ->select([
                'game_guest_auto',
                'game_guest_national_id',
                'game_guest_plus_minus',
                'game_guest_points',
                'game_guest_team_id',
                'game_home_auto',
                'game_home_national_id',
                'game_home_plus_minus',
                'game_home_points',
                'game_home_team_id',
                'game_id',
                'game_played',
                'game_schedule_id',
            ])
            ->where(['or', ['game_home_team_id' => $teamId], ['game_guest_team_id' => $teamId]])
            ->andWhere(['schedule_season_id' => $seasonId])
            ->orderBy(['schedule_date' => SORT_ASC]);
    }

    /**
     * @param ActiveQuery $query
     */
    private static function withNationalQuery(ActiveQuery $query): void
    {
        $query
            ->with([
                'country' => static function (ActiveQuery $query) {
                    $query->select([
                        'country_id',
                        'country_name',
                    ]);
                },
                'nationalType' => static function (ActiveQuery $query) {
                    $query->select([
                        'national_type_id',
                        'national_type_name',
                    ]);
                },
            ])
            ->select([
                'national_id',
                'national_country_id',
                'national_national_type_id',
            ]);
    }

    /**
     * @param ActiveQuery $query
     */
    private static function withTeamQuery(ActiveQuery $query): void
    {
        $query
            ->with([
                'stadium' => static function (ActiveQuery $query) {
                    $query
                        ->with([
                            'city' => static function (ActiveQuery $query) {
                                $query
                                    ->with([
                                        'country' => static function (ActiveQuery $query) {
                                            $query->select([
                                                'country_id',
                                                'country_name',
                                            ]);
                                        },
                                    ])
                                    ->select([
                                        'city_id',
                                        'city_country_id',
                                        'city_name',
                                    ]);
                            },
                        ])
                        ->select([
                            'stadium_id',
                            'stadium_city_id',
                        ]);
                },
            ])
            ->select([
                'team_id',
                'team_name',
                'team_stadium_id',
            ]);
    }
}
