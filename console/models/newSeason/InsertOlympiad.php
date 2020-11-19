<?php

namespace console\models\newSeason;

use common\models\Game;
use common\models\National;
use common\models\NationalType;
use common\models\Olympiad;
use common\models\ParticipantOlympiad;
use common\models\Schedule;
use common\models\Season;
use common\models\Stage;
use common\models\TournamentType;
use Exception;
use Yii;

/**
 * Class InsertOlympiad
 * @package console\models\newSeason
 */
class InsertOlympiad
{
    /**
     * @return bool
     * @throws Exception
     */
    public function execute(): bool
    {
        $seasonId = Season::getCurrentSeason() + 1;

        if ($seasonId % 4) {
            return true;
        }

        $data = [];
        $nationalArray = National::find()
            ->where(['national_national_type_id' => NationalType::MAIN])
            ->orderBy(['national_id' => SORT_ASC])
            ->all();
        foreach ($nationalArray as $national) {
            $data[] = [$national->national_id, $seasonId];
        }

        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                ParticipantOlympiad::tableName(),
                [
                    'participant_olympiad_national_id',
                    'participant_olympiad_season_id',
                ],
                $data
            )
            ->execute();

        $nationalArray = $this->lot();

        foreach ($nationalArray as $group => $national) {
            foreach ($national as $place => $item) {
                $model = new Olympiad();
                $model->olympiad_group = $group;
                $model->olympiad_place = $place + 1;
                $model->olympiad_season_id = $seasonId;
                $model->olympiad_national_id = $item;
                $model->olympiad_national_type_id = NationalType::MAIN;
                $model->save();
            }
        }

        $stageArray = Schedule::find()
            ->where([
                'schedule_season_id' => $seasonId,
                'schedule_tournament_type_id' => TournamentType::OLYMPIAD
            ])
            ->andWhere(['between', 'schedule_stage_id', Stage::TOUR_OLYMPIAD_1, Stage::TOUR_OLYMPIAD_5])
            ->orderBy(['schedule_id' => SORT_ASC])
            ->limit(5)
            ->all();
        $schedule_id_1 = $stageArray[0]->schedule_id;
        $schedule_id_2 = $stageArray[1]->schedule_id;
        $schedule_id_3 = $stageArray[2]->schedule_id;
        $schedule_id_4 = $stageArray[3]->schedule_id;
        $schedule_id_5 = $stageArray[4]->schedule_id;

        $groupArray = Olympiad::find()
            ->where(['olympiad_season_id' => $seasonId])
            ->groupBy(['olympiad_group'])
            ->orderBy(['olympiad_group' => SORT_ASC])
            ->all();
        foreach ($groupArray as $group) {
            $nationalArray = Olympiad::find()
                ->where(['olympiad_season_id' => $seasonId, 'olympiad_group' => $group->olympiad_group])
                ->orderBy('RAND()')
                ->all();

            $national_id_1 = $nationalArray[0]->olympiad_national_id;
            $national_id_2 = $nationalArray[1]->olympiad_national_id;
            $national_id_3 = $nationalArray[2]->olympiad_national_id;
            $national_id_4 = $nationalArray[3]->olympiad_national_id;
            $national_id_5 = $nationalArray[4]->olympiad_national_id;
            $national_id_6 = $nationalArray[5]->olympiad_national_id;

            $data = [
                [0, $national_id_1, $national_id_2, $schedule_id_1],
                [0, $national_id_5, $national_id_3, $schedule_id_1],
                [0, $national_id_4, $national_id_6, $schedule_id_1],
                [0, $national_id_3, $national_id_1, $schedule_id_2],
                [0, $national_id_4, $national_id_5, $schedule_id_2],
                [0, $national_id_6, $national_id_2, $schedule_id_2],
                [0, $national_id_1, $national_id_4, $schedule_id_3],
                [0, $national_id_2, $national_id_3, $schedule_id_3],
                [0, $national_id_5, $national_id_6, $schedule_id_3],
                [0, $national_id_4, $national_id_2, $schedule_id_4],
                [0, $national_id_5, $national_id_1, $schedule_id_4],
                [0, $national_id_6, $national_id_3, $schedule_id_4],
                [0, $national_id_1, $national_id_6, $schedule_id_5],
                [0, $national_id_2, $national_id_5, $schedule_id_5],
                [0, $national_id_3, $national_id_4, $schedule_id_5],
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

        return true;
    }

    /**
     * @return array
     */
    private function lot(): array
    {
        $nationalArray = $this->prepare();
        $nationalArray = $this->all($nationalArray);

        return $nationalArray;
    }

    /**
     * @return array
     */
    private function prepare(): array
    {
        $nationalArray = ParticipantOlympiad::find()
            ->joinWith(['national'])
            ->orderBy(['national_power_vs' => SORT_DESC])
            ->limit(24)
            ->all();

        $nationalResultArray = [[], [], [], [], [], []];

        $countNational = count($nationalArray);
        $limitOne = $countNational / 6;
        $limitTwo = $limitOne * 2;
        $limitThree = $limitOne * 3;
        $limitFour = $limitOne * 4;
        $limitFive = $limitOne * 5;

        for ($i = 0; $i < $countNational; $i++) {
            if ($i < $limitOne) {
                $nationalResultArray[0][] = $nationalArray[$i];
            } elseif ($i < $limitTwo) {
                $nationalResultArray[1][] = $nationalArray[$i];
            } elseif ($i < $limitThree) {
                $nationalResultArray[2][] = $nationalArray[$i];
            } elseif ($i < $limitFour) {
                $nationalResultArray[3][] = $nationalArray[$i];
            } elseif ($i < $limitFive) {
                $nationalResultArray[4][] = $nationalArray[$i];
            } else {
                $nationalResultArray[5][] = $nationalArray[$i];
            }
        }

        return $nationalResultArray;
    }

    /**
     * @param array $nationalArray
     * @return array
     */
    private function all(array $nationalArray): array
    {
        if (!$national_result_array = $this->group($nationalArray)) {
            $national_result_array = $this->all($nationalArray);
        }

        return $national_result_array;
    }

    /**
     * @param array $nationalArray
     * @param array $nationalResultArray
     * @param int $groupNumber
     * @return array
     */
    private function group(array $nationalArray, array $nationalResultArray = [], int $groupNumber = 1): array
    {
        $national1 = $this->national($nationalArray, 0);
        $national2 = $this->national($nationalArray, 1);
        $national3 = $this->national($nationalArray, 2);
        $national4 = $this->national($nationalArray, 3);
        $national5 = $this->national($nationalArray, 4);
        $national6 = $this->national($nationalArray, 5);

        $nationalResultArray[$groupNumber] = [
            $national1['national_id'],
            $national2['national_id'],
            $national3['national_id'],
            $national4['national_id'],
            $national5['national_id'],
            $national6['national_id'],
        ];

        unset($nationalArray[0][$national1['i']]);
        unset($nationalArray[1][$national2['i']]);
        unset($nationalArray[2][$national3['i']]);
        unset($nationalArray[3][$national4['i']]);
        unset($nationalArray[4][$national5['i']]);
        unset($nationalArray[5][$national6['i']]);

        $nationalArray = array(
            array_values($nationalArray[0]),
            array_values($nationalArray[1]),
            array_values($nationalArray[2]),
            array_values($nationalArray[3]),
            array_values($nationalArray[4]),
            array_values($nationalArray[5]),
        );

        if (count($nationalArray[0])) {
            $groupNumber++;
            $nationalResultArray = $this->group($nationalArray, $nationalResultArray, $groupNumber);
        }

        return $nationalResultArray;
    }

    /**
     * @param array $national_array
     * @param int $key
     * @return array
     */
    private function national(array $national_array, int $key = 0): array
    {
        $national = array_rand($national_array[$key]);

        return [
            'i' => $national,
            'national_id' => $national_array[$key][$national]->participant_olympiad_national_id,
        ];
    }
}
