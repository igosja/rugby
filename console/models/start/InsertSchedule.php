<?php

// TODO refactor

namespace console\models\start;

use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\Stage;
use common\models\db\TournamentType;
use Yii;
use yii\db\Exception;

/**
 * Class InsertSchedule
 * @package console\models\start
 */
class InsertSchedule
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $seasonId = Season::getCurrentSeason();
        $scheduleFriendlyArray = [6, 13, 20, 27, 34, 41, 48];
        $scheduleOffSeasonArray = [0, 1, 2, 3, 4, 5, 7, 8, 9, 10, 11, 12];
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
            Stage::TOUR_13,
            Stage::TOUR_14,
            Stage::TOUR_15,
            Stage::TOUR_16,
            Stage::TOUR_17,
            Stage::TOUR_18,
            Stage::FRIENDLY,
            Stage::TOUR_19,
            Stage::TOUR_20,
            Stage::TOUR_21,
            Stage::TOUR_22,
            Stage::TOUR_23,
            Stage::TOUR_24,
            Stage::FRIENDLY,
            Stage::TOUR_25,
            Stage::TOUR_26,
            Stage::TOUR_27,
            Stage::TOUR_28,
            Stage::TOUR_29,
            Stage::TOUR_30,
            Stage::FRIENDLY,
        ];

        $startDate = strtotime('Mon') + 12 * 60 * 60;

        $data = [];

        for ($i = 0; $i < 49; $i++) {
            $date = $startDate + $i * 24 * 60 * 60;
            $conference = false;

            if (in_array($i, $scheduleFriendlyArray)) {
                $tournamentType = TournamentType::FRIENDLY;
            } elseif (in_array($i, $scheduleOffSeasonArray)) {
                $tournamentType = TournamentType::OFF_SEASON;
            } else {
                $conference = true;
                $tournamentType = TournamentType::CHAMPIONSHIP;
            }

            $data[] = [$date, $seasonId, $scheduleStageArray[$i], $tournamentType];
            if ($conference) {
                $data[] = [$date, $seasonId, $scheduleStageArray[$i], TournamentType::CONFERENCE];
            }
        }

        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                Schedule::tableName(),
                ['schedule_date', 'schedule_season_id', 'schedule_stage_id', 'schedule_tournament_type_id'],
                $data
            )
            ->execute();
    }
}
