<?php

namespace console\models\newSeason;

use common\models\Game;
use common\models\NationalType;
use common\models\Schedule;
use common\models\Season;
use common\models\TournamentType;
use common\models\WorldCup;
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
            ->orderBy(['national_type_id' => SORT_ASC])
            ->all();
        foreach ($nationalTypeArray as $nationalType) {
            $nationalTypeId = $nationalType->national_type_id;

            WorldCup::updateAll(
                ['world_cup_place' => new Expression('`world_cup_id`-((CEIL(`world_cup_id`/12)-1)*12)')],
                [
                    'world_cup_place' => 0,
                    'world_cup_national_type_id' => $nationalTypeId,
                    'world_cup_season_id' => $seasonId
                ]
            );

            $scheduleArray = Schedule::find()
                ->where(['schedule_season_id' => $seasonId, 'schedule_tournament_type_id' => TournamentType::NATIONAL])
                ->orderBy(['schedule_id' => SORT_ASC])
                ->all();

            $schedule_id_01 = $scheduleArray[0]->schedule_id;
            $schedule_id_02 = $scheduleArray[1]->schedule_id;
            $schedule_id_03 = $scheduleArray[2]->schedule_id;
            $schedule_id_04 = $scheduleArray[3]->schedule_id;
            $schedule_id_05 = $scheduleArray[4]->schedule_id;
            $schedule_id_06 = $scheduleArray[5]->schedule_id;
            $schedule_id_07 = $scheduleArray[6]->schedule_id;
            $schedule_id_08 = $scheduleArray[7]->schedule_id;
            $schedule_id_09 = $scheduleArray[8]->schedule_id;
            $schedule_id_10 = $scheduleArray[9]->schedule_id;
            $schedule_id_11 = $scheduleArray[10]->schedule_id;

            $divisionArray = WorldCup::find()
                ->select(['world_cup_division_id'])
                ->where([
                    'world_cup_season_id' => $seasonId,
                    'world_cup_national_type_id' => $nationalTypeId,
                ])
                ->groupBy(['world_cup_division_id'])
                ->orderBy(['world_cup_division_id' => SORT_ASC])
                ->all();

            foreach ($divisionArray as $division) {
                /**
                 * @var WorldCup $division
                 */
                $worldCupArray = WorldCup::find()
                    ->where([
                        'world_cup_national_type_id' => $nationalTypeId,
                        'world_cup_division_id' => $division->world_cup_division_id,
                        'world_cup_season_id' => $seasonId,
                    ])
                    ->orderBy('RAND()')
                    ->all();

                $national_id_01 = $worldCupArray[0]->world_cup_national_id;
                $national_id_02 = $worldCupArray[1]->world_cup_national_id;
                $national_id_03 = $worldCupArray[2]->world_cup_national_id;
                $national_id_04 = $worldCupArray[3]->world_cup_national_id;
                $national_id_05 = $worldCupArray[4]->world_cup_national_id;
                $national_id_06 = $worldCupArray[5]->world_cup_national_id;
                $national_id_07 = $worldCupArray[6]->world_cup_national_id;
                $national_id_08 = $worldCupArray[7]->world_cup_national_id;
                $national_id_09 = $worldCupArray[8]->world_cup_national_id;
                $national_id_10 = $worldCupArray[9]->world_cup_national_id;
                $national_id_11 = $worldCupArray[10]->world_cup_national_id;
                $national_id_12 = $worldCupArray[11]->world_cup_national_id;

                $data = [
                    [0, $national_id_01, $national_id_02, $schedule_id_01],
                    [0, $national_id_07, $national_id_12, $schedule_id_01],
                    [0, $national_id_08, $national_id_06, $schedule_id_01],
                    [0, $national_id_09, $national_id_05, $schedule_id_01],
                    [0, $national_id_10, $national_id_04, $schedule_id_01],
                    [0, $national_id_11, $national_id_03, $schedule_id_01],
                    [0, $national_id_03, $national_id_01, $schedule_id_02],
                    [0, $national_id_04, $national_id_11, $schedule_id_02],
                    [0, $national_id_05, $national_id_10, $schedule_id_02],
                    [0, $national_id_06, $national_id_09, $schedule_id_02],
                    [0, $national_id_07, $national_id_08, $schedule_id_02],
                    [0, $national_id_12, $national_id_02, $schedule_id_02],
                    [0, $national_id_01, $national_id_04, $schedule_id_03],
                    [0, $national_id_02, $national_id_03, $schedule_id_03],
                    [0, $national_id_08, $national_id_12, $schedule_id_03],
                    [0, $national_id_09, $national_id_07, $schedule_id_03],
                    [0, $national_id_10, $national_id_06, $schedule_id_03],
                    [0, $national_id_11, $national_id_05, $schedule_id_03],
                    [0, $national_id_04, $national_id_02, $schedule_id_04],
                    [0, $national_id_05, $national_id_01, $schedule_id_04],
                    [0, $national_id_06, $national_id_11, $schedule_id_04],
                    [0, $national_id_07, $national_id_10, $schedule_id_04],
                    [0, $national_id_08, $national_id_09, $schedule_id_04],
                    [0, $national_id_12, $national_id_03, $schedule_id_04],
                    [0, $national_id_01, $national_id_06, $schedule_id_05],
                    [0, $national_id_02, $national_id_05, $schedule_id_05],
                    [0, $national_id_03, $national_id_04, $schedule_id_05],
                    [0, $national_id_09, $national_id_12, $schedule_id_05],
                    [0, $national_id_10, $national_id_08, $schedule_id_05],
                    [0, $national_id_11, $national_id_07, $schedule_id_05],
                    [0, $national_id_05, $national_id_03, $schedule_id_06],
                    [0, $national_id_06, $national_id_02, $schedule_id_06],
                    [0, $national_id_07, $national_id_01, $schedule_id_06],
                    [0, $national_id_08, $national_id_11, $schedule_id_06],
                    [0, $national_id_09, $national_id_10, $schedule_id_06],
                    [0, $national_id_12, $national_id_04, $schedule_id_06],
                    [0, $national_id_01, $national_id_08, $schedule_id_07],
                    [0, $national_id_02, $national_id_07, $schedule_id_07],
                    [0, $national_id_03, $national_id_06, $schedule_id_07],
                    [0, $national_id_04, $national_id_05, $schedule_id_07],
                    [0, $national_id_10, $national_id_12, $schedule_id_07],
                    [0, $national_id_11, $national_id_09, $schedule_id_07],
                    [0, $national_id_06, $national_id_04, $schedule_id_08],
                    [0, $national_id_07, $national_id_03, $schedule_id_08],
                    [0, $national_id_08, $national_id_02, $schedule_id_08],
                    [0, $national_id_09, $national_id_01, $schedule_id_08],
                    [0, $national_id_10, $national_id_11, $schedule_id_08],
                    [0, $national_id_12, $national_id_05, $schedule_id_08],
                    [0, $national_id_01, $national_id_10, $schedule_id_09],
                    [0, $national_id_02, $national_id_09, $schedule_id_09],
                    [0, $national_id_03, $national_id_08, $schedule_id_09],
                    [0, $national_id_04, $national_id_07, $schedule_id_09],
                    [0, $national_id_05, $national_id_06, $schedule_id_09],
                    [0, $national_id_11, $national_id_12, $schedule_id_09],
                    [0, $national_id_07, $national_id_05, $schedule_id_10],
                    [0, $national_id_08, $national_id_04, $schedule_id_10],
                    [0, $national_id_09, $national_id_03, $schedule_id_10],
                    [0, $national_id_10, $national_id_02, $schedule_id_10],
                    [0, $national_id_11, $national_id_01, $schedule_id_10],
                    [0, $national_id_12, $national_id_06, $schedule_id_10],
                    [0, $national_id_01, $national_id_12, $schedule_id_11],
                    [0, $national_id_02, $national_id_11, $schedule_id_11],
                    [0, $national_id_03, $national_id_10, $schedule_id_11],
                    [0, $national_id_04, $national_id_09, $schedule_id_11],
                    [0, $national_id_05, $national_id_08, $schedule_id_11],
                    [0, $national_id_06, $national_id_07, $schedule_id_11],
                ];

                Yii::$app->db
                    ->createCommand()
                    ->batchInsert(
                        Game::tableName(),
                        ['game_bonus_home', 'game_home_national_id', 'game_guest_national_id', 'game_schedule_id'],
                        $data
                    )
                    ->execute();
            }
        }
    }
}