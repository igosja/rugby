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
                'teamGuest',
                'teamHome',
                'schedule' => static function (ActiveQuery $query) {
                    $query
                        ->with([
                            'tournamentType',
                        ]);
                },
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
                'teamGuest',
                'teamHome',
                'schedule' => static function (ActiveQuery $query) {
                    $query
                        ->with([
                            'tournamentType',
                        ]);
                },
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
                            'stage',
                            'tournamentType',
                        ]);
                },
                'teamHome' => static function (ActiveQuery $query) {
                    self::withTeamQuery($query);
                },
                'teamGuest' => static function (ActiveQuery $query) {
                    self::withTeamQuery($query);
                },
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
                'country',
                'nationalType',
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
                                        'country',
                                    ]);
                            },
                        ]);
                },
            ]);
    }
}
