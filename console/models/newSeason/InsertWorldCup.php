<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Game;
use common\models\db\NationalType;
use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\TournamentType;
use common\models\db\WorldCup;
use Exception;
use Yii;
use yii\db\Expression;

/**
 * Class InsertWorldCup
 * @package console\models\newSeason
 */
class InsertWorldCup
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $seasonId = Season::getCurrentSeason() + 1;

        $nationalTypeArray = NationalType::find()
            ->orderBy(['id' => SORT_ASC])
            ->all();
        foreach ($nationalTypeArray as $nationalType) {
            $nationalTypeId = $nationalType->id;

            WorldCup::updateAll(
                ['place' => new Expression('`id`-((CEIL(`id`/10)-1)*10)')],
                [
                    'place' => 0,
                    'national_type_id' => $nationalTypeId,
                    'season_id' => $seasonId
                ]
            );

            $scheduleArray = Schedule::find()
                ->where(['season_id' => $seasonId, 'tournament_type_id' => TournamentType::NATIONAL])
                ->orderBy(['id' => SORT_ASC])
                ->all();

            $schedule_id_01 = $scheduleArray[0]->id;
            $schedule_id_02 = $scheduleArray[1]->id;
            $schedule_id_03 = $scheduleArray[2]->id;
            $schedule_id_04 = $scheduleArray[3]->id;
            $schedule_id_05 = $scheduleArray[4]->id;
            $schedule_id_06 = $scheduleArray[5]->id;
            $schedule_id_07 = $scheduleArray[6]->id;
            $schedule_id_08 = $scheduleArray[7]->id;
            $schedule_id_09 = $scheduleArray[8]->id;

            $divisionArray = WorldCup::find()
                ->select(['division_id'])
                ->where([
                    'season_id' => $seasonId,
                    'national_type_id' => $nationalTypeId,
                ])
                ->groupBy(['division_id'])
                ->orderBy(['division_id' => SORT_ASC])
                ->all();

            foreach ($divisionArray as $division) {
                /**
                 * @var WorldCup $division
                 */
                $worldCupArray = WorldCup::find()
                    ->where([
                        'national_type_id' => $nationalTypeId,
                        'division_id' => $division->division_id,
                        'season_id' => $seasonId,
                    ])
                    ->orderBy('RAND()')
                    ->all();

                $national_id_01 = $worldCupArray[0]->national_id;
                $national_id_02 = $worldCupArray[1]->national_id;
                $national_id_03 = $worldCupArray[2]->national_id;
                $national_id_04 = $worldCupArray[3]->national_id;
                $national_id_05 = $worldCupArray[4]->national_id;
                $national_id_06 = $worldCupArray[5]->national_id;
                $national_id_07 = $worldCupArray[6]->national_id;
                $national_id_08 = $worldCupArray[7]->national_id;
                $national_id_09 = $worldCupArray[8]->national_id;
                $national_id_10 = $worldCupArray[9]->national_id;

                $data = [
                    [0, $national_id_01, $national_id_02, $schedule_id_01],
                    [0, $national_id_06, $national_id_10, $schedule_id_01],
                    [0, $national_id_07, $national_id_05, $schedule_id_01],
                    [0, $national_id_08, $national_id_04, $schedule_id_01],
                    [0, $national_id_09, $national_id_03, $schedule_id_01],
                    [0, $national_id_03, $national_id_01, $schedule_id_02],
                    [0, $national_id_04, $national_id_09, $schedule_id_02],
                    [0, $national_id_05, $national_id_08, $schedule_id_02],
                    [0, $national_id_06, $national_id_07, $schedule_id_02],
                    [0, $national_id_10, $national_id_02, $schedule_id_02],
                    [0, $national_id_01, $national_id_04, $schedule_id_03],
                    [0, $national_id_02, $national_id_03, $schedule_id_03],
                    [0, $national_id_07, $national_id_10, $schedule_id_03],
                    [0, $national_id_08, $national_id_06, $schedule_id_03],
                    [0, $national_id_09, $national_id_05, $schedule_id_03],
                    [0, $national_id_04, $national_id_02, $schedule_id_04],
                    [0, $national_id_05, $national_id_01, $schedule_id_04],
                    [0, $national_id_06, $national_id_09, $schedule_id_04],
                    [0, $national_id_07, $national_id_08, $schedule_id_04],
                    [0, $national_id_10, $national_id_03, $schedule_id_04],
                    [0, $national_id_01, $national_id_06, $schedule_id_05],
                    [0, $national_id_02, $national_id_05, $schedule_id_05],
                    [0, $national_id_03, $national_id_04, $schedule_id_05],
                    [0, $national_id_08, $national_id_10, $schedule_id_05],
                    [0, $national_id_09, $national_id_07, $schedule_id_05],
                    [0, $national_id_05, $national_id_03, $schedule_id_06],
                    [0, $national_id_06, $national_id_02, $schedule_id_06],
                    [0, $national_id_07, $national_id_01, $schedule_id_06],
                    [0, $national_id_08, $national_id_09, $schedule_id_06],
                    [0, $national_id_10, $national_id_04, $schedule_id_06],
                    [0, $national_id_01, $national_id_08, $schedule_id_07],
                    [0, $national_id_02, $national_id_07, $schedule_id_07],
                    [0, $national_id_03, $national_id_06, $schedule_id_07],
                    [0, $national_id_04, $national_id_05, $schedule_id_07],
                    [0, $national_id_09, $national_id_10, $schedule_id_07],
                    [0, $national_id_06, $national_id_04, $schedule_id_08],
                    [0, $national_id_07, $national_id_03, $schedule_id_08],
                    [0, $national_id_08, $national_id_02, $schedule_id_08],
                    [0, $national_id_09, $national_id_01, $schedule_id_08],
                    [0, $national_id_10, $national_id_05, $schedule_id_08],
                    [0, $national_id_01, $national_id_10, $schedule_id_09],
                    [0, $national_id_02, $national_id_09, $schedule_id_09],
                    [0, $national_id_03, $national_id_08, $schedule_id_09],
                    [0, $national_id_04, $national_id_07, $schedule_id_09],
                    [0, $national_id_05, $national_id_06, $schedule_id_09],
                ];

                Yii::$app->db
                    ->createCommand()
                    ->batchInsert(
                        Game::tableName(),
                        ['bonus_home', 'home_national_id', 'guest_national_id', 'schedule_id'],
                        $data
                    )
                    ->execute();
            }
        }
    }
}