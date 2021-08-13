<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\Schedule;
use common\models\db\StatisticTeam;
use common\models\db\TournamentType;
use Exception;
use Yii;

/**
 * Class TeamToStatistic
 * @package console\models\generator
 */
class TeamToStatistic
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $insertData = [];

        $gameArray = Game::find()
            ->with(['schedule', 'stadium.team', 'homeNational.worldCup', 'homeTeam.championship'])
            ->where(['played' => null])
            ->andWhere([
                'schedule_id' => Schedule::find()
                    ->select('id')
                    ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($gameArray as $game) {
            /**
             * @var Game $game
             */
            $federationId = $game->homeTeam->championship->federation_id ?? null;
            $divisionId = $game->homeTeam->championship->division_id ?? null;

            if (TournamentType::NATIONAL === $game->schedule->tournament_type_id) {
                $divisionId = $game->homeNational->worldCup->division_id ?? null;
            }

            if (in_array($game->schedule->tournament_type_id, [
                TournamentType::FRIENDLY,
                TournamentType::CONFERENCE,
                TournamentType::LEAGUE,
                TournamentType::OFF_SEASON
            ], true)) {
                $federationId = null;
                $divisionId = null;
            }

            $check = StatisticTeam::find()->where([
                'federation_id' => $federationId,
                'division_id' => $divisionId,
                'national_id' => $game->home_national_id,
                'season_id' => $game->schedule->season_id,
                'team_id' => $game->home_team_id,
                'tournament_type_id' => $game->schedule->tournament_type_id,
            ])->count();

            if (!$check) {
                $insertData[] = [
                    $federationId,
                    $divisionId,
                    $game->home_national_id,
                    $game->schedule->season_id,
                    $game->home_team_id,
                    $game->schedule->tournament_type_id,
                ];
            }

            $check = StatisticTeam::find()->where([
                'federation_id' => $federationId,
                'division_id' => $divisionId,
                'national_id' => $game->guest_national_id,
                'season_id' => $game->schedule->season_id,
                'team_id' => $game->guest_team_id,
                'tournament_type_id' => $game->schedule->tournament_type_id,
            ])->count();

            if (!$check) {
                $insertData[] = [
                    $federationId,
                    $divisionId,
                    $game->guest_national_id,
                    $game->schedule->season_id,
                    $game->guest_team_id,
                    $game->schedule->tournament_type_id,
                ];
            }
        }

        Yii::$app->db->createCommand()->batchInsert(
            StatisticTeam::tableName(),
            ['federation_id', 'division_id', 'national_id', 'season_id', 'team_id', 'tournament_type_id'],
            $insertData
        )->execute();
    }
}
