<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\League;
use common\models\db\ParticipantLeague;
use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\Stage;
use common\models\db\TournamentType;

/**
 * Class LeagueOut
 * @package console\models\generator
 */
class LeagueOut
{
    /**
     * @return void
     */
    public function execute(): void
    {
        $schedule = Schedule::find()
            ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ->andWhere(['tournament_type_id' => TournamentType::LEAGUE])
            ->limit(1)
            ->one();
        if (!$schedule) {
            return;
        }

        $seasonId = Season::getCurrentSeason();

        if (in_array($schedule->stage_id, [
            Stage::QUALIFY_1,
            Stage::QUALIFY_2,
            Stage::QUALIFY_3,
            Stage::ROUND_OF_16,
            Stage::QUARTER,
            Stage::SEMI,
            Stage::FINAL_GAME,
        ], true)) {
            /**
             * @var Game[] $gameArray
             */
            $gameArray = Game::find()
                ->where(['schedule_id' => $schedule->id])
                ->orderBy(['id' => SORT_ASC])
                ->all();
            foreach ($gameArray as $game) {
                /**
                 * @var Game[] $prevArray
                 */
                $prevArray = Game::find()
                    ->joinWith(['schedule'])
                    ->where([
                        'or',
                        [
                            'home_team_id' => $game->home_team_id,
                            'guest_team_id' => $game->guest_team_id
                        ],
                        [
                            'home_team_id' => $game->guest_team_id,
                            'guest_team_id' => $game->home_team_id
                        ],
                    ])
                    ->andWhere(['not', ['played' => null]])
                    ->andWhere([
                        'tournament_type_id' => TournamentType::LEAGUE,
                        'stage_id' => $schedule->stage_id,
                        'season_id' => $seasonId,
                    ])
                    ->orderBy(['game.id' => SORT_ASC])
                    ->all();

                if (count($prevArray) > 1) {
                    $homeScore = 0;
                    $guestScore = 0;

                    foreach ($prevArray as $prev) {
                        if ($game->home_team_id === $prev->home_team_id) {
                            $homeScore += $prev->home_point;
                            $guestScore += $prev->guest_point;
                        } else {
                            $homeScore += $prev->guest_point;
                            $guestScore += $prev->home_point;
                        }
                    }

                    if ($homeScore > $guestScore) {
                        $looseTeamId = $game->guest_team_id;
                    } else {
                        $looseTeamId = $game->home_team_id;
                    }

                    ParticipantLeague::updateAll(
                        ['stage_out_id' => $schedule->stage_id],
                        ['team_id' => $looseTeamId, 'season_id' => $seasonId]
                    );
                }
            }
        } elseif (Stage::TOUR_LEAGUE_6 === $schedule->stage_id) {
            /**
             * @var League[] $leagueArray
             */
            $leagueArray = League::find()
                ->where(['place' => [3, 4], 'season_id' => $seasonId])
                ->orderBy(['id' => SORT_ASC])
                ->all();
            foreach ($leagueArray as $league) {
                ParticipantLeague::updateAll(
                    ['stage_out_id' => $league->place],
                    [
                        'team_id' => $league->team_id,
                        'season_id' => $seasonId
                    ]
                );
            }

            /**
             * @var League[] $leagueArray
             */
            $leagueArray = League::find()
                ->where(['place' => [1, 2], 'season_id' => $seasonId])
                ->orderBy(['id' => SORT_ASC])
                ->all();
            foreach ($leagueArray as $league) {
                $stage8 = 0;
                $stage4 = 0;
                $stage2 = 0;
                $stage1 = 1;

                if (1 === $league->place) {
                    if (1 === $league->group) {
                        $stage8 = 1;
                        $stage4 = 1;
                        $stage2 = 1;
                    } elseif (2 === $league->group) {
                        $stage8 = 5;
                        $stage4 = 3;
                        $stage2 = 2;
                    } elseif (3 === $league->group) {
                        $stage8 = 2;
                        $stage4 = 1;
                        $stage2 = 1;
                    } elseif (4 === $league->group) {
                        $stage8 = 6;
                        $stage4 = 3;
                        $stage2 = 2;
                    } elseif (5 === $league->group) {
                        $stage8 = 3;
                        $stage4 = 2;
                        $stage2 = 1;
                    } elseif (6 === $league->group) {
                        $stage8 = 7;
                        $stage4 = 4;
                        $stage2 = 2;
                    } elseif (7 === $league->group) {
                        $stage8 = 4;
                        $stage4 = 2;
                        $stage2 = 1;
                    } elseif (8 === $league->group) {
                        $stage8 = 8;
                        $stage4 = 4;
                        $stage2 = 2;
                    }
                } elseif (2 === $league->place) {
                    if (1 === $league->group) {
                        $stage8 = 8;
                        $stage4 = 4;
                        $stage2 = 2;
                    } elseif (2 === $league->group) {
                        $stage8 = 1;
                        $stage4 = 1;
                        $stage2 = 1;
                    } elseif (3 === $league->group) {
                        $stage8 = 5;
                        $stage4 = 3;
                        $stage2 = 2;
                    } elseif (4 === $league->group) {
                        $stage8 = 2;
                        $stage4 = 1;
                        $stage2 = 1;
                    } elseif (5 === $league->group) {
                        $stage8 = 6;
                        $stage4 = 3;
                        $stage2 = 2;
                    } elseif (6 === $league->group) {
                        $stage8 = 3;
                        $stage4 = 2;
                        $stage2 = 1;
                    } elseif (7 === $league->group) {
                        $stage8 = 7;
                        $stage4 = 4;
                        $stage2 = 2;
                    } elseif (8 === $league->group) {
                        $stage8 = 4;
                        $stage4 = 2;
                        $stage2 = 1;
                    }
                }

                ParticipantLeague::updateAll(
                    [
                        'stage_1' => $stage1,
                        'stage_2' => $stage2,
                        'stage_4' => $stage4,
                        'stage_8' => $stage8,
                    ],
                    [
                        'team_id' => $league->team_id,
                        'season_id' => $seasonId,
                    ]
                );
            }
        }
    }
}
