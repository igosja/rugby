<?php

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\Schedule;
use common\models\db\StatisticPlayer;
use common\models\db\TournamentType;
use Exception;

/**
 * Class LineupToStatistic
 * @package console\models\generator
 */
class LineupToStatistic
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $gameArray = Game::find()
            ->with(['schedule', 'lineups', 'homeNational.worldCup', 'homeTeam.championship'])
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
                $divisionId = $game->homeNational->worldCup->division ?? null;
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

            foreach ($game->lineups as $lineup) {
                $check = StatisticPlayer::find()->where([
                    'federation_id' => $federationId,
                    'division_id' => $divisionId,
                    'national_id' => $lineup->national_id,
                    'player_id' => $lineup->player_id,
                    'season_id' => $game->schedule->season_id,
                    'team_id' => $lineup->team_id,
                    'tournament_type_id' => $game->schedule->tournament_type_id,
                ])->count();

                if (!$check) {
                    $model = new StatisticPlayer();
                    $model->federation_id = $federationId;
                    $model->division_id = $divisionId;
                    $model->national_id = $lineup->national_id;
                    $model->player_id = $lineup->player_id;
                    $model->season_id = $game->schedule->season_id;
                    $model->team_id = $lineup->team_id;
                    $model->tournament_type_id = $game->schedule->tournament_type_id;
                    $model->save();
                }
            }
        }
    }
}
