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
use common\models\db\Weather;
use Exception;
use Yii;

/**
 * Class LeagueLot
 * @package console\models\generator
 */
class LeagueLot
{
    /**
     * @return void
     * @throws \yii\db\Exception
     * @throws Exception
     */
    public function execute(): void
    {
        /**
         * @var Schedule $schedule
         * @var Schedule $check
         * @var Schedule[] $stageArray
         */
        $schedule = Schedule::find()
            ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ->andWhere(['tournament_type_id' => TournamentType::LEAGUE])
            ->limit(1)
            ->one();
        if (!$schedule) {
            return;
        }

        $seasonId = Season::getCurrentSeason();

        if (Stage::QUALIFY_1 === $schedule->stage_id) {
            $check = Schedule::find()
                ->select(['stage_id'])
                ->where(['tournament_type_id' => TournamentType::LEAGUE])
                ->andWhere(['>', 'id', $schedule->id])
                ->orderBy(['id' => SORT_ASC])
                ->limit(1)
                ->one();
            if ($check && Stage::QUALIFY_2 === $check->stage_id) {
                $teamArray = $this->lot(Stage::QUALIFY_2);

                $stageArray = Schedule::find()
                    ->where([
                        'season_id' => $seasonId,
                        'stage_id' => Stage::QUALIFY_2,
                        'tournament_type_id' => TournamentType::LEAGUE,
                    ])
                    ->orderBy(['schedule_id' => SORT_ASC])
                    ->limit(2)
                    ->all();

                foreach ($teamArray as $item) {
                    $model = new Game();
                    $model->guest_team_id = $item['guest'];
                    $model->home_team_id = $item['home'];
                    $model->schedule_id = $stageArray[0]->id;
                    $model->weather_id = Weather::getRandomWeatherId();
                    $model->save();

                    $model = new Game();
                    $model->guest_team_id = $item['home'];
                    $model->home_team_id = $item['guest'];
                    $model->schedule_id = $stageArray[1]->id;
                    $model->weather_id = Weather::getRandomWeatherId();
                    $model->save();
                }

                $sql = "UPDATE `game`
                        LEFT JOIN `team`
                        ON `home_team_id`=`team`.`id`
                        SET `game`.`stadium_id`=`team`.`stadium_id`
                        WHERE `schedule_id` IN (" . $stageArray[0]->id . ", " . $stageArray[1]->id . ")";
                Yii::$app->db->createCommand($sql)->execute();
            }
        } elseif (Stage::QUALIFY_2 === $schedule->stage_id) {
            $check = Schedule::find()
                ->select(['stage_id'])
                ->where(['tournament_type_id' => TournamentType::LEAGUE])
                ->andWhere(['>', 'id', $schedule->id])
                ->orderBy(['id' => SORT_ASC])
                ->limit(1)
                ->one();
            if ($check && Stage::QUALIFY_3 === $check->stage_id) {
                $teamArray = $this->lot(Stage::QUALIFY_3);

                $stageArray = Schedule::find()
                    ->where([
                        'season_id' => $seasonId,
                        'stage_id' => Stage::QUALIFY_3,
                        'tournament_type_id' => TournamentType::LEAGUE,
                    ])
                    ->orderBy(['id' => SORT_ASC])
                    ->limit(2)
                    ->all();

                foreach ($teamArray as $item) {
                    $model = new Game();
                    $model->guest_team_id = $item['guest'];
                    $model->home_team_id = $item['home'];
                    $model->schedule_id = $stageArray[0]->id;
                    $model->weather_id = Weather::getRandomWeatherId();
                    $model->save();

                    $model = new Game();
                    $model->guest_team_id = $item['home'];
                    $model->home_team_id = $item['guest'];
                    $model->schedule_id = $stageArray[1]->id;
                    $model->weather_id = Weather::getRandomWeatherId();
                    $model->save();
                }

                $sql = "UPDATE `game`
                        LEFT JOIN `team`
                        ON `home_team_id`=`team`.`id`
                        SET `game`.`stadium_id`=`team`.`stadium_id`
                        WHERE `schedule_id` IN (" . $stageArray[0]->id . ", " . $stageArray[1]->id . ")";
                Yii::$app->db->createCommand($sql)->execute();
            }
        } elseif (Stage::QUALIFY_3 === $schedule->stage_id) {
            $check = Schedule::find()
                ->select(['stage_id'])
                ->where(['tournament_type_id' => TournamentType::LEAGUE])
                ->andWhere(['>', 'id', $schedule->id])
                ->orderBy(['id' => SORT_ASC])
                ->limit(1)
                ->one();
            if ($check && Stage::TOUR_LEAGUE_1 === $check->stage_id) {
                $teamArray = $this->lot(Stage::TOUR_LEAGUE_1);

                foreach ($teamArray as $group => $team) {
                    foreach ($team as $place => $item) {
                        $model = new League();
                        $model->group = $group;
                        $model->place = $place + 1;
                        $model->season_id = $seasonId;
                        $model->team_id = $item;
                        $model->save();
                    }
                }

                $stageArray = Schedule::find()
                    ->where([
                        'season_id' => $seasonId,
                        'tournament_type_id' => TournamentType::LEAGUE
                    ])
                    ->andWhere(['between', 'stage_id', Stage::TOUR_LEAGUE_1, Stage::TOUR_LEAGUE_6])
                    ->orderBy(['id' => SORT_ASC])
                    ->limit(6)
                    ->all();
                $schedule_id_1 = $stageArray[0]->id;
                $schedule_id_2 = $stageArray[1]->id;
                $schedule_id_3 = $stageArray[2]->id;
                $schedule_id_4 = $stageArray[3]->id;
                $schedule_id_5 = $stageArray[4]->id;
                $schedule_id_6 = $stageArray[5]->id;

                /**
                 * @var League[] $groupArray
                 */
                $groupArray = League::find()
                    ->where(['season_id' => $seasonId])
                    ->groupBy(['group'])
                    ->orderBy(['group' => SORT_ASC])
                    ->all();
                foreach ($groupArray as $group) {
                    /**
                     * @var League[] $teamArray
                     */
                    $teamArray = League::find()
                        ->where(['season_id' => $seasonId, 'group' => $group->league_group])
                        ->orderBy('RAND()')
                        ->all();

                    $team_id_1 = $teamArray[0]->team_id;
                    $team_id_2 = $teamArray[1]->team_id;
                    $team_id_3 = $teamArray[2]->team_id;
                    $team_id_4 = $teamArray[3]->team_id;

                    $stadium_id_1 = $teamArray[0]->team->stadium_id;
                    $stadium_id_2 = $teamArray[1]->team->stadium_id;
                    $stadium_id_3 = $teamArray[2]->team->stadium_id;
                    $stadium_id_4 = $teamArray[3]->team->stadium_id;

                    $data = [
                        [$team_id_2, $team_id_1, $schedule_id_1, $stadium_id_2],
                        [$team_id_4, $team_id_3, $schedule_id_1, $stadium_id_4],
                        [$team_id_1, $team_id_3, $schedule_id_2, $stadium_id_1],
                        [$team_id_2, $team_id_4, $schedule_id_2, $stadium_id_2],
                        [$team_id_3, $team_id_2, $schedule_id_3, $stadium_id_3],
                        [$team_id_4, $team_id_1, $schedule_id_3, $stadium_id_4],
                        [$team_id_1, $team_id_2, $schedule_id_4, $stadium_id_1],
                        [$team_id_3, $team_id_4, $schedule_id_4, $stadium_id_3],
                        [$team_id_3, $team_id_1, $schedule_id_5, $stadium_id_3],
                        [$team_id_4, $team_id_2, $schedule_id_5, $stadium_id_4],
                        [$team_id_2, $team_id_3, $schedule_id_6, $stadium_id_2],
                        [$team_id_1, $team_id_4, $schedule_id_6, $stadium_id_1],
                    ];

                    foreach ($data as $i => $value) {
                        $data[$i][] = Weather::getRandomWeatherId();
                    }

                    Yii::$app->db
                        ->createCommand()
                        ->batchInsert(
                            Game::tableName(),
                            ['home_team_id', 'guest_team_id', 'schedule_id', 'stadium_id', 'weather_id'],
                            $data
                        )
                        ->execute();
                }
            }
        } elseif (Stage::TOUR_LEAGUE_6 === $schedule->stage_id) {
            $stageArray = Schedule::find()
                ->where([
                    'season_id' => $seasonId,
                    'stage_id' => Stage::ROUND_OF_16,
                    'tournament_type_id' => TournamentType::LEAGUE,
                ])
                ->orderBy(['id' => SORT_ASC])
                ->limit(2)
                ->all();
            for ($i = 1; $i <= 8; $i++) {
                /**
                 * @var ParticipantLeague[] $teamArray
                 */
                $teamArray = ParticipantLeague::find()
                    ->where([
                        'season_id' => $seasonId,
                        'stage_8' => $i,
                        'stage_out_id' => null,
                    ])
                    ->orderBy(['id' => SORT_ASC])
                    ->limit(2)
                    ->all();

                $model = new Game();
                $model->guest_team_id = $teamArray[1]->team_id;
                $model->home_team_id = $teamArray[0]->team_id;
                $model->schedule_id = $stageArray[0]->id;
                $model->stadium_id = $teamArray[0]->team->stadium_id;
                $model->weather_id = Weather::getRandomWeatherId();
                $model->save();

                $model = new Game();
                $model->guest_team_id = $teamArray[0]->team_id;
                $model->home_team_id = $teamArray[1]->team_id;
                $model->schedule_id = $stageArray[1]->id;
                $model->stadium_id = $teamArray[1]->team->stadium_id;
                $model->weather_id = Weather::getRandomWeatherId();
                $model->save();
            }
        } elseif (Stage::ROUND_OF_16 === $schedule->stage_id) {
            $check = Schedule::find()
                ->select(['stage_id'])
                ->where(['tournament_type_id' => TournamentType::LEAGUE])
                ->andWhere(['>', 'id', $schedule->id])
                ->orderBy(['id' => SORT_ASC])
                ->limit(1)
                ->one();
            if ($check && Stage::QUARTER === $check->stage_id) {
                $stageArray = Schedule::find()
                    ->where([
                        'season_id' => $seasonId,
                        'stage_id' => Stage::QUARTER,
                        'tournament_type_id' => TournamentType::LEAGUE,
                    ])
                    ->orderBy(['id' => SORT_ASC])
                    ->limit(2)
                    ->all();
                for ($i = 1; $i <= 4; $i++) {
                    /**
                     * @var ParticipantLeague[] $teamArray
                     */
                    $teamArray = ParticipantLeague::find()
                        ->where([
                            'season_id' => $seasonId,
                            'stage_4' => $i,
                            'stage_id' => null,
                        ])
                        ->orderBy(['id' => SORT_ASC])
                        ->limit(2)
                        ->all();

                    $model = new Game();
                    $model->guest_team_id = $teamArray[1]->team_id;
                    $model->home_team_id = $teamArray[0]->team_id;
                    $model->schedule_id = $stageArray[0]->id;
                    $model->stadium_id = $teamArray[0]->team->stadium_id;
                    $model->weather_id = Weather::getRandomWeatherId();
                    $model->save();

                    $model = new Game();
                    $model->guest_team_id = $teamArray[0]->team_id;
                    $model->home_team_id = $teamArray[1]->team_id;
                    $model->schedule_id = $stageArray[1]->id;
                    $model->stadium_id = $teamArray[1]->team->stadium_id;
                    $model->weather_id = Weather::getRandomWeatherId();
                    $model->save();
                }
            }
        } elseif (Stage::QUARTER === $schedule->stage_id) {
            $check = Schedule::find()
                ->select(['stage_id'])
                ->where(['tournament_type_id' => TournamentType::LEAGUE])
                ->andWhere(['>', 'id', $schedule->id])
                ->orderBy(['id' => SORT_ASC])
                ->limit(1)
                ->one();
            if ($check && Stage::SEMI === $check->stage_id) {
                $stageArray = Schedule::find()
                    ->where([
                        'season_id' => $seasonId,
                        'stage_id' => Stage::SEMI,
                        'tournament_type_id' => TournamentType::LEAGUE,
                    ])
                    ->orderBy(['id' => SORT_ASC])
                    ->limit(2)
                    ->all();
                for ($i = 1; $i <= 2; $i++) {
                    /**
                     * @var ParticipantLeague[] $teamArray
                     */
                    $teamArray = ParticipantLeague::find()
                        ->where([
                            'season_id' => $seasonId,
                            'stage_2' => $i,
                            'stage_id' => null,
                        ])
                        ->orderBy(['id' => SORT_ASC])
                        ->limit(2)
                        ->all();

                    $model = new Game();
                    $model->guest_team_id = $teamArray[1]->team_id;
                    $model->home_team_id = $teamArray[0]->team_id;
                    $model->schedule_id = $stageArray[0]->id;
                    $model->stadium_id = $teamArray[0]->team->stadium_id;
                    $model->weather_id = Weather::getRandomWeatherId();
                    $model->save();

                    $model = new Game();
                    $model->guest_team_id = $teamArray[0]->team_id;
                    $model->home_team_id = $teamArray[1]->team_id;
                    $model->schedule_id = $stageArray[1]->id;
                    $model->stadium_id = $teamArray[1]->team->stadium_id;
                    $model->weather_id = Weather::getRandomWeatherId();
                    $model->save();
                }
            }
        } elseif (Stage::SEMI === $schedule->stage_id) {
            $check = Schedule::find()
                ->select(['stage_id'])
                ->where(['tournament_type_id' => TournamentType::LEAGUE])
                ->andWhere(['>', 'id', $schedule->id])
                ->orderBy(['id' => SORT_ASC])
                ->limit(1)
                ->one();
            if ($check && Stage::FINAL_GAME === $check->stage_id) {
                $stageArray = Schedule::find()
                    ->where([
                        'season_id' => $seasonId,
                        'stage_id' => Stage::FINAL_GAME,
                        'tournament_type_id' => TournamentType::LEAGUE,
                    ])
                    ->orderBy(['id' => SORT_ASC])
                    ->limit(2)
                    ->all();
                /**
                 * @var ParticipantLeague[] $teamArray
                 */
                $teamArray = ParticipantLeague::find()
                    ->where([
                        'season_id' => $seasonId,
                        'stage_1' => 1,
                        'stage_id' => null,
                    ])
                    ->orderBy(['id' => SORT_ASC])
                    ->limit(2)
                    ->all();

                $model = new Game();
                $model->guest_team_id = $teamArray[1]->team_id;
                $model->home_team_id = $teamArray[0]->team_id;
                $model->schedule_id = $stageArray[0]->id;
                $model->stadium_id = $teamArray[0]->team->stadium_id;
                $model->weather_id = Weather::getRandomWeatherId();
                $model->save();

                $model = new Game();
                $model->guest_team_id = $teamArray[0]->team_id;
                $model->home_team_id = $teamArray[1]->team_id;
                $model->schedule_id = $stageArray[1]->id;
                $model->stadium_id = $teamArray[1]->team->stadium_id;
                $model->weather_id = Weather::getRandomWeatherId();
                $model->save();
            }
        }
    }

    /**
     * @param int $stageId
     * @return array
     */
    private function lot(int $stageId): array
    {
        $teamArray = $this->prepare($stageId);
        $teamArray = $this->all($teamArray, $stageId);

        return $teamArray;
    }

    /**
     * @param int $stageId
     * @return array
     */
    private function prepare(int $stageId): array
    {
        $seasonId = Season::getCurrentSeason();

        if (Stage::QUALIFY_2 === $stageId) {
            $participantLeagueArray = ParticipantLeague::find()
                ->joinwith(['team'])
                ->where([
                    'season_id' => $seasonId,
                    'stage_id' => null,
                    'stage_in' => [Stage::QUALIFY_1, Stage::QUALIFY_2],
                ])
                ->orderBy(['power_vs' => SORT_DESC])
                ->all();
        } elseif (Stage::QUALIFY_3 === $stageId) {
            $participantLeagueArray = ParticipantLeague::find()
                ->joinwith(['team'])
                ->where([
                    'season_id' => $seasonId,
                    'stage_id' => null,
                    'stage_in' => [Stage::QUALIFY_1, Stage::QUALIFY_2, Stage::QUALIFY_3],
                ])
                ->orderBy(['power_vs' => SORT_DESC])
                ->all();
        } else {
            $participantLeagueArray = ParticipantLeague::find()
                ->joinwith(['team'])
                ->where([
                    'season_id' => $seasonId,
                    'stage_id' => null,
                ])
                ->orderBy(['power_vs' => SORT_DESC])
                ->limit(32)
                ->all();
        }

        $teamResultArray = [[], [], [], []];

        $countParticipantLeague = count($participantLeagueArray);
        $limitQuarter = $countParticipantLeague / 4;
        $limitHalf = $countParticipantLeague / 2;
        $limitThree = $limitQuarter * 3;

        foreach ($participantLeagueArray as $i => $iValue) {
            if (in_array($stageId, [Stage::QUALIFY_2, Stage::QUALIFY_3], true)) {
                if ($i < $limitHalf) {
                    $teamResultArray[0][] = $iValue;
                } else {
                    $teamResultArray[1][] = $iValue;
                }
            } else {
                if ($i < $limitQuarter) {
                    $teamResultArray[0][] = $iValue;
                } elseif ($i < $limitHalf) {
                    $teamResultArray[1][] = $iValue;
                } elseif ($i < $limitThree) {
                    $teamResultArray[2][] = $iValue;
                } else {
                    $teamResultArray[3][] = $iValue;
                }
            }
        }

        return $teamResultArray;
    }

    /**
     * @param array $teamArray
     * @param int $stageId
     * @return array
     */
    private function all(array $teamArray, int $stageId): array
    {
        if (in_array($stageId, [Stage::QUALIFY_2, Stage::QUALIFY_3], true)) {
            if (!$team_result_array = $this->one($teamArray)) {
                $team_result_array = $this->all($teamArray, $stageId);
            }
        } else {
            if (!$team_result_array = $this->group($teamArray)) {
                $team_result_array = $this->all($teamArray, $stageId);
            }
        }

        return $team_result_array;
    }

    /**
     * @param array $teamArray
     * @param array $teamResultArray
     * @return array
     */
    private function one(array $teamArray, array $teamResultArray = []): array
    {
        $homeTeam = $this->teamHome($teamArray);
        $guestTeam = $this->teamGuest($teamArray, $homeTeam);

        if (!$guestTeam) {
            return [];
        }

        $teamResultArray[] = [
            'home' => $homeTeam['team_id'],
            'guest' => $guestTeam['team_id']
        ];

        unset($teamArray[0][$homeTeam['i']]);
        unset($teamArray[1][$guestTeam['i']]);

        $teamArray = array(
            array_values($teamArray[0]),
            array_values($teamArray[1]),
        );

        if (count($teamArray[0])) {
            $teamResultArray = $this->one($teamArray, $teamResultArray);
        }

        return $teamResultArray;
    }

    /**
     * @param array $teamArray
     * @return array
     */
    private function teamHome(array $teamArray): array
    {
        $team = array_rand($teamArray[0]);

        return [
            'i' => $team,
            'team_id' => $teamArray[0][$team]->team_id,
            'country_id' => $teamArray[0][$team]->team->stadium->city->country_id,
        ];
    }

    /**
     * @param array $teamArray
     * @param array $homeTeam
     * @return array
     */
    private function teamGuest(array $teamArray, array $homeTeam): array
    {
        $shuffleArray = $teamArray[1];

        shuffle($shuffleArray);

        foreach ($shuffleArray as $item) {
            if ($item->team->stadium->city->country_id !== $homeTeam['country_id']) {
                foreach ($teamArray[1] as $i => $iValue) {
                    if ($iValue->team_id === $item->team_id) {
                        return [
                            'i' => $i,
                            'team_id' => $iValue->team_id,
                        ];
                    }
                }
            }
        }

        return [];
    }

    /**
     * @param array $teamArray
     * @param array $teamResultArray
     * @param int $groupNumber
     * @return array
     */
    private function group(array $teamArray, array $teamResultArray = [], int $groupNumber = 1): array
    {
        $team1 = $this->team1($teamArray);
        $team2 = $this->team2($teamArray, $team1);

        if (!$team2) {
            return [];
        }

        $team3 = $this->team3($teamArray, $team1, $team2);

        if (!$team3) {
            return [];
        }

        $team4 = $this->team4($teamArray, $team1, $team2, $team3);

        if (!$team4) {
            return [];
        }

        $teamResultArray[$groupNumber] = array(
            $team1['team_id'],
            $team2['team_id'],
            $team3['team_id'],
            $team4['team_id'],
        );

        unset($teamArray[0][$team1['i']], $teamArray[1][$team2['i']], $teamArray[2][$team3['i']], $teamArray[3][$team4['i']]);

        $teamArray = array(
            array_values($teamArray[0]),
            array_values($teamArray[1]),
            array_values($teamArray[2]),
            array_values($teamArray[3]),
        );

        if (count($teamArray[0])) {
            $groupNumber++;
            $teamResultArray = $this->group($teamArray, $teamResultArray, $groupNumber);
        }

        return $teamResultArray;
    }

    /**
     * @param array $team_array
     * @return array
     */
    private function team1(array $team_array): array
    {
        $team = array_rand($team_array[0]);

        return [
            'i' => $team,
            'team_id' => $team_array[0][$team]->team_id,
            'country_id' => $team_array[0][$team]->team->stadium->city->country_id,
            'user_id' => $team_array[0][$team]->team->user_id,
        ];
    }

    /**
     * @param array $teamArray
     * @param array $team_1
     * @return array
     */
    private function team2(array $teamArray, array $team_1): array
    {
        $shuffleArray = $teamArray[1];

        shuffle($shuffleArray);

        foreach ($shuffleArray as $item) {
            /**
             * @var ParticipantLeague $item
             */
            if ($item->team->stadium->city->country_id !== $team_1['country_id'] && $item->team->user_id !== $team_1['user_id']) {
                foreach ($teamArray[1] as $i => $iValue) {
                    if ($iValue->team_id === $item->team_id) {
                        return [
                            'i' => $i,
                            'team_id' => $iValue->team_id,
                            'country_id' => $iValue->team->stadium->city->country_id,
                            'user_id' => $iValue->team->user_id,
                        ];
                    }
                }
            }
        }

        return [];
    }

    /**
     * @param array $teamArray
     * @param array $team_1
     * @param array $team_2
     * @return array
     */
    private function team3(array $teamArray, array $team_1, array $team_2): array
    {
        $shuffleArray = $teamArray[2];

        shuffle($shuffleArray);

        foreach ($shuffleArray as $item) {
            /**
             * @var ParticipantLeague $item
             */
            if (!in_array($item->team->stadium->city->country_id, [$team_1['country_id'], $team_2['country_id']], true) && !in_array($item->team->user_id, [$team_1['user_id'], $team_2['user_id']], true)) {
                foreach ($teamArray[2] as $i => $iValue) {
                    if ($iValue->team_id === $item->team_id) {
                        return [
                            'i' => $i,
                            'team_id' => $iValue->team_id,
                            'country_id' => $iValue->team->stadium->city->country_id,
                            'user_id' => $iValue->team->user_id,
                        ];
                    }
                }
            }
        }

        return [];
    }

    /**
     * @param array $teamArray
     * @param array $team_1
     * @param array $team_2
     * @param array $team_3
     * @return array
     */
    private function team4(array $teamArray, array $team_1, array $team_2, array $team_3): array
    {
        $shuffleArray = $teamArray[3];

        shuffle($shuffleArray);

        foreach ($shuffleArray as $item) {
            /**
             * @var ParticipantLeague $item
             */
            if (!in_array($item->team->stadium->city->country_id, [$team_1['country_id'], $team_2['country_id'], $team_3['country_id']], true) && !in_array($item->team->user_id, [$team_1['user_id'], $team_2['user_id'], $team_3['user_id']], true)) {
                foreach ($teamArray[3] as $i => $iValue) {
                    if ($iValue->team_id === $item->team_id) {
                        return [
                            'i' => $i,
                            'team_id' => $iValue->team_id,
                        ];
                    }
                }
            }
        }

        return [];
    }
}
