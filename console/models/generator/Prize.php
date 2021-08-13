<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Championship;
use common\models\db\Conference;
use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\OffSeason;
use common\models\db\ParticipantLeague;
use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\Stage;
use common\models\db\TournamentType;
use common\models\db\WorldCup;
use Exception;

/**
 * Class Prize
 * @package console\models\generator
 */
class Prize
{
    /**
     * @throws Exception
     */
    public function execute(): void
    {
        /**
         * @var Schedule[] $scheduleArray
         */
        $scheduleArray = Schedule::find()
            ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ->all();

        $seasonId = Season::getCurrentSeason();

        foreach ($scheduleArray as $schedule) {
            if (TournamentType::OFF_SEASON === $schedule->tournament_type_id && Stage::TOUR_12 === $schedule->stage_id) {
                $offSeasonArray = OffSeason::find()
                    ->with(['team'])
                    ->where(['season_id' => $seasonId])
                    ->orderBy(['id' => SORT_ASC])
                    ->each();
                foreach ($offSeasonArray as $offSeason) {
                    /**
                     * @var OffSeason $offSeason
                     */
                    $prize = round(2000000 * (0.98 ** ($offSeason->place - 1)));

                    Finance::log([
                        'finance_text_id' => FinanceText::INCOME_PRIZE_OFF_SEASON,
                        'team_id' => $offSeason->team_id,
                        'value' => $prize,
                        'value_after' => $offSeason->team->finance + $prize,
                        'value_before' => $offSeason->team->finance,
                    ]);

                    $offSeason->team->finance += $prize;
                    $offSeason->team->save();
                }
            } elseif (TournamentType::CHAMPIONSHIP === $schedule->tournament_type_id && Stage::TOUR_30 === $schedule->stage_id) {
                $championshipArray = Championship::find()
                    ->with(['team'])
                    ->where(['season_id' => $seasonId])
                    ->orderBy(['id' => SORT_ASC])
                    ->each();
                foreach ($championshipArray as $championship) {
                    /**
                     * @var Championship $championship
                     */
                    $prize = round(
                        20000000 * (0.98 ** (($championship->place - 1) + ($championship->division_id - 1) * 16))
                    );

                    Finance::log([
                        'finance_text_id' => FinanceText::INCOME_PRIZE_CHAMPIONSHIP,
                        'team_id' => $championship->team_id,
                        'value' => $prize,
                        'value_after' => $championship->team->finance + $prize,
                        'value_before' => $championship->team->finance
                    ]);

                    $championship->team->finance += $prize;
                    $championship->team->save();
                }

                $conferenceArray = Conference::find()
                    ->with(['team'])
                    ->where(['season_id' => $seasonId])
                    ->orderBy(['id' => SORT_ASC])
                    ->each();
                foreach ($conferenceArray as $conference) {
                    /**
                     * @var Conference $conference
                     */
                    $prize = round(10000000 * (0.98 ** ($conference->place - 1)));

                    Finance::log([
                        'finance_text_id' => FinanceText::INCOME_PRIZE_CONFERENCE,
                        'team_id' => $conference->team_id,
                        'value' => $prize,
                        'value_after' => $conference->team->finance + $prize,
                        'value_before' => $conference->team->finance
                    ]);

                    $conference->team->finance += $prize;
                    $conference->team->save();
                }
            } elseif (TournamentType::NATIONAL === $schedule->tournament_type_id && Stage::TOUR_11 === $schedule->stage_id) {
                $worldCupArray = WorldCup::find()
                    ->with(['national'])
                    ->where(['season_id' => $seasonId])
                    ->each();
                foreach ($worldCupArray as $worldCup) {
                    /**
                     * @var WorldCup $worldCup
                     */
                    $prize = round((25 - ($worldCup->national_type_id - 1) * 5) * 1000000 * (0.98 ** (($worldCup->place - 1) + ($worldCup->division_id - 1) * 12)));

                    Finance::log([
                        'finance_text_id' => FinanceText::INCOME_PRIZE_WORLD_CUP,
                        'national_id' => $worldCup->national_id,
                        'value' => $prize,
                        'value_after' => $worldCup->national->finance + $prize,
                        'value_before' => $worldCup->national->finance,
                    ]);

                    $worldCup->national->finance += $prize;
                    $worldCup->national->save();
                }
            } elseif (TournamentType::LEAGUE === $schedule->tournament_type_id && in_array($schedule->stage_id, [
                    Stage::QUALIFY_1,
                    Stage::QUALIFY_2,
                    Stage::QUALIFY_3,
                    Stage::ROUND_OF_16,
                    Stage::QUARTER,
                    Stage::SEMI
                ], true)) {
                $nextStage = Schedule::find()
                    ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")>CURDATE()')
                    ->andWhere(['tournament_type_id' => TournamentType::LEAGUE])
                    ->orderBy(['id' => SORT_ASC])
                    ->limit(1)
                    ->one();
                if ($nextStage->stage_id !== $schedule->stage_id) {
                    /**
                     * @var ParticipantLeague[] $leagueArray
                     */
                    $leagueArray = ParticipantLeague::find()
                        ->with(['team'])
                        ->where([
                            'season_id' => $seasonId,
                            'stage_out_id' => $schedule->stage_id
                        ])
                        ->all();
                    foreach ($leagueArray as $league) {
                        if (Stage::SEMI === $league->stage_out_id) {
                            $prize = 21000000;
                        } elseif (Stage::QUARTER === $league->stage_out_id) {
                            $prize = 19000000;
                        } elseif (Stage::ROUND_OF_16 === $league->stage_out_id) {
                            $prize = 17000000;
                        } elseif (Stage::QUALIFY_3 === $league->stage_out_id) {
                            $prize = 9000000;
                        } elseif (Stage::QUALIFY_2 === $league->stage_out_id) {
                            $prize = 7000000;
                        } else {
                            $prize = 5000000;
                        }

                        Finance::log([
                            'finance_text_id' => FinanceText::INCOME_PRIZE_LEAGUE,
                            'team_id' => $league->team_id,
                            'value' => $prize,
                            'value_after' => $league->team->finance + $prize,
                            'value_before' => $league->team->finance,
                        ]);

                        $league->team->finance += $prize;
                        $league->team->save();
                    }
                }
            } elseif (TournamentType::LEAGUE === $schedule->tournament_type_id && Stage::TOUR_LEAGUE_6 === $schedule->stage_id) {
                /**
                 * @var ParticipantLeague[] $leagueArray
                 */
                $leagueArray = ParticipantLeague::find()
                    ->with(['team'])
                    ->where(['season_id' => $seasonId, 'stage_out_id' => [3, 4]])
                    ->orderBy(['id' => SORT_ASC])
                    ->all();
                foreach ($leagueArray as $league) {
                    if (4 === $league->stage_out_id) {
                        $prize = 13000000;
                    } else {
                        $prize = 15000000;
                    }

                    Finance::log([
                        'finance_text_id' => FinanceText::INCOME_PRIZE_LEAGUE,
                        'team_id' => $league->team_id,
                        'value' => $prize,
                        'value_after' => $league->team->finance + $prize,
                        'value_before' => $league->team->finance,
                    ]);

                    $league->team->finance += $prize;
                    $league->team->save();
                }
            } elseif (TournamentType::LEAGUE === $schedule->tournament_type_id && Stage::FINAL_GAME === $schedule->stage_id) {
                $nextStage = Schedule::find()
                    ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")>CURDATE()')
                    ->andWhere(['tournament_type_id' => TournamentType::LEAGUE])
                    ->orderBy(['id' => SORT_ASC])
                    ->limit(1)
                    ->one();
                if ($nextStage) {
                    continue;
                }
                /**
                 * @var ParticipantLeague[] $leagueArray
                 */
                $leagueArray = ParticipantLeague::find()
                    ->with(['team'])
                    ->where([
                        'season_id' => $seasonId,
                        'stage_out_id' => [Stage::FINAL_GAME, null]
                    ])
                    ->all();
                foreach ($leagueArray as $league) {
                    if (Stage::FINAL_GAME === $league->stage_out_id) {
                        $prize = 23000000;
                    } else {
                        $prize = 25000000;
                    }

                    Finance::log([
                        'finance_text_id' => FinanceText::INCOME_PRIZE_LEAGUE,
                        'team_id' => $league->team_id,
                        'value' => $prize,
                        'value_after' => $league->team->finance + $prize,
                        'value_before' => $league->team->finance,
                    ]);

                    $league->team->finance += $prize;
                    $league->team->save();
                }
            }
        }
    }
}
