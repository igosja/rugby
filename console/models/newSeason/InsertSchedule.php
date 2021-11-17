<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\Stage;
use common\models\db\TournamentType;
use Yii;
use yii\db\Exception;

/**
 * Class InsertSchedule
 * @package console\models\newSeason
 */
class InsertSchedule
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $seasonId = Season::getCurrentSeason() + 1;
        $scheduleFriendlyArray = [6, 13, 20, 27, 34, 41, 48, 55, 62, 69, 76, 82, 83];
        $scheduleOffSeasonArray = [0, 1, 2, 3, 4, 5, 7, 8, 9, 10, 11, 12];
        $scheduleLeagueArray = [15, 17, 22, 24, 29, 31, 36, 38, 43, 45, 50, 52, 57, 59, 64, 66, 71, 73, 78, 80];
        $scheduleWorldCupArray = [19, 26, 33, 40, 47, 54, 61, 68, 75];
        $scheduleStageArray = [
            Stage::TOUR_1,
            Stage::TOUR_2,
            Stage::TOUR_3,
            Stage::TOUR_4,
            Stage::TOUR_5,
            Stage::TOUR_6,
            Stage::FRIENDLY,
            Stage::TOUR_7,
            Stage::TOUR_8,
            Stage::TOUR_9,
            Stage::TOUR_10,
            Stage::TOUR_11,
            Stage::TOUR_12,
            Stage::FRIENDLY,
            Stage::TOUR_1,
            Stage::QUALIFY_1,
            Stage::TOUR_2,
            Stage::QUALIFY_1,
            Stage::TOUR_3,
            Stage::TOUR_1,
            Stage::FRIENDLY,
            Stage::TOUR_4,
            Stage::QUALIFY_2,
            Stage::TOUR_5,
            Stage::QUALIFY_2,
            Stage::TOUR_6,
            Stage::TOUR_2,
            Stage::FRIENDLY,
            Stage::TOUR_7,
            Stage::QUALIFY_3,
            Stage::TOUR_8,
            Stage::QUALIFY_3,
            Stage::TOUR_9,
            Stage::TOUR_3,
            Stage::FRIENDLY,
            Stage::TOUR_10,
            Stage::TOUR_LEAGUE_1,
            Stage::TOUR_11,
            Stage::TOUR_LEAGUE_2,
            Stage::TOUR_12,
            Stage::TOUR_4,
            Stage::FRIENDLY,
            Stage::TOUR_13,
            Stage::TOUR_LEAGUE_3,
            Stage::TOUR_14,
            Stage::TOUR_LEAGUE_4,
            Stage::TOUR_15,
            Stage::TOUR_5,
            Stage::FRIENDLY,
            Stage::TOUR_16,
            Stage::TOUR_LEAGUE_5,
            Stage::TOUR_17,
            Stage::TOUR_LEAGUE_6,
            Stage::TOUR_18,
            Stage::TOUR_6,
            Stage::FRIENDLY,
            Stage::TOUR_19,
            Stage::ROUND_OF_16,
            Stage::TOUR_20,
            Stage::ROUND_OF_16,
            Stage::TOUR_21,
            Stage::TOUR_7,
            Stage::FRIENDLY,
            Stage::TOUR_22,
            Stage::QUARTER,
            Stage::TOUR_23,
            Stage::QUARTER,
            Stage::TOUR_24,
            Stage::TOUR_8,
            Stage::FRIENDLY,
            Stage::TOUR_25,
            Stage::SEMI,
            Stage::TOUR_26,
            Stage::SEMI,
            Stage::TOUR_27,
            Stage::TOUR_9,
            Stage::FRIENDLY,
            Stage::TOUR_28,
            Stage::FINAL_GAME,
            Stage::TOUR_29,
            Stage::FINAL_GAME,
            Stage::TOUR_30,
            Stage::FRIENDLY,
            Stage::FRIENDLY,
        ];
        $scheduleConferenceStageArray = [
            Stage::TOUR_1,
            Stage::TOUR_2,
            Stage::TOUR_3,
            Stage::TOUR_4,
            Stage::TOUR_5,
            Stage::TOUR_6,
            Stage::FRIENDLY,
            Stage::TOUR_7,
            Stage::TOUR_8,
            Stage::TOUR_9,
            Stage::TOUR_10,
            Stage::TOUR_11,
            Stage::TOUR_12,
            Stage::FRIENDLY,
            Stage::TOUR_1,
            Stage::QUALIFY_1,
            Stage::TOUR_2,
            Stage::QUALIFY_1,
            Stage::TOUR_3,
            Stage::TOUR_1,
            Stage::FRIENDLY,
            Stage::TOUR_4,
            Stage::QUALIFY_2,
            Stage::TOUR_5,
            Stage::QUALIFY_2,
            Stage::TOUR_6,
            Stage::TOUR_2,
            Stage::FRIENDLY,
            Stage::TOUR_7,
            Stage::QUALIFY_3,
            Stage::TOUR_8,
            Stage::QUALIFY_3,
            Stage::TOUR_9,
            Stage::TOUR_3,
            Stage::FRIENDLY,
            Stage::TOUR_10,
            Stage::TOUR_LEAGUE_1,
            Stage::TOUR_11,
            Stage::TOUR_LEAGUE_2,
            Stage::TOUR_12,
            Stage::TOUR_4,
            Stage::FRIENDLY,
            Stage::TOUR_13,
            Stage::TOUR_LEAGUE_3,
            Stage::TOUR_14,
            Stage::TOUR_LEAGUE_4,
            Stage::TOUR_15,
            Stage::TOUR_5,
            Stage::FRIENDLY,
            Stage::TOUR_16,
            Stage::TOUR_LEAGUE_5,
            Stage::TOUR_17,
            Stage::TOUR_LEAGUE_6,
            Stage::TOUR_18,
            Stage::TOUR_6,
            Stage::FRIENDLY,
            Stage::TOUR_19,
            Stage::ROUND_OF_16,
            Stage::TOUR_20,
            Stage::ROUND_OF_16,
            Stage::TOUR_21,
            Stage::TOUR_7,
            Stage::FRIENDLY,
            Stage::TOUR_22,
            Stage::QUARTER,
            Stage::TOUR_23,
            Stage::QUARTER,
            Stage::TOUR_24,
            Stage::TOUR_8,
            Stage::FRIENDLY,
            Stage::TOUR_25,
            Stage::SEMI,
            Stage::TOUR_26,
            Stage::SEMI,
            Stage::TOUR_27,
            Stage::TOUR_9,
            Stage::FRIENDLY,
            Stage::TOUR_28,
            Stage::FINAL_GAME,
            Stage::TOUR_29,
            Stage::FINAL_GAME,
            Stage::TOUR_30,
            Stage::FRIENDLY,
            Stage::FRIENDLY,
        ];

        $startDate = strtotime('Mon') + 9 * 60 * 60;

        $data = [];

        for ($i = 0; $i < 84; $i++) {
            $date = $startDate + $i * 24 * 60 * 60;
            $conference = 0;

            if (in_array($i, $scheduleFriendlyArray)) {
                $tournamentType = TournamentType::FRIENDLY;
            } elseif (in_array($i, $scheduleOffSeasonArray)) {
                $tournamentType = TournamentType::OFF_SEASON;
            } elseif (in_array($i, $scheduleLeagueArray)) {
                $tournamentType = TournamentType::LEAGUE;
            } elseif (in_array($i, $scheduleWorldCupArray)) {
                $tournamentType = TournamentType::NATIONAL;
            } else {
                $conference = true;
                $tournamentType = TournamentType::CHAMPIONSHIP;
            }

            $data[] = [$date, $seasonId, $scheduleStageArray[$i], $tournamentType];
            if ($conference) {
                $data[] = [$date, $seasonId, $scheduleConferenceStageArray[$i], TournamentType::CONFERENCE];
            }
        }

        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                Schedule::tableName(),
                ['date', 'season_id', 'stage_id', 'tournament_type_id'],
                $data
            )
            ->execute();
    }
}
