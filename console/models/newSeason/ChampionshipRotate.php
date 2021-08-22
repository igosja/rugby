<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Championship;
use common\models\db\Conference;
use common\models\db\Country;
use common\models\db\Division;
use common\models\db\ParticipantChampionship;
use common\models\db\Season;
use common\models\db\Stage;
use Yii;
use yii\db\Exception;

/**
 * Class ChampionshipRotate
 * @package console\models\newSeason
 */
class ChampionshipRotate
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $seasonId = Season::getCurrentSeason();

        $divisionArray = Division::find()
            ->orderBy(['division_id' => SORT_ASC])
            ->all();

        $countryArray = Country::find()
            ->joinWith(['city'])
            ->where(['!=', 'city_id', 0])
            ->groupBy(['country_id'])
            ->orderBy(['country_id' => SORT_ASC])
            ->all();
        foreach ($countryArray as $country) {
            $rotateArray = [];

            foreach ($divisionArray as $division) {
                $rotateChampionship = [];

                if (Division::D1 == $division->division_id) {
                    $championshipArray = Championship::find()
                        ->where([
                            'championship_division_id' => $division->division_id,
                            'championship_country_id' => $country->country_id,
                            'championship_season_id' => $seasonId,
                        ])
                        ->orderBy(['championship_place' => SORT_ASC])
                        ->limit(14)
                        ->all();
                    foreach ($championshipArray as $team) {
                        $rotateChampionship[] = $team->championship_team_id;
                    }

                    $participantArray = ParticipantChampionship::find()
                        ->where([
                            'participant_championship_country_id' => $country->country_id,
                            'participant_championship_division_id' => $division->division_id + 1,
                            'participant_championship_season_id' => $seasonId,
                            'participant_championship_stage_id' => [0, Stage::FINAL_GAME],
                        ])
                        ->orderBy(['participant_championship_id' => SORT_ASC])
                        ->all();
                    foreach ($participantArray as $team) {
                        $rotateChampionship[] = $team->participant_championship_team_id;
                    }
                } else {
                    $championshipArray = Championship::find()
                        ->where([
                            'championship_division_id' => $division->division_id,
                            'championship_country_id' => $country->country_id,
                            'championship_season_id' => $seasonId,
                        ])
                        ->andWhere([
                            'not',
                            [
                                'championship_team_id' => ParticipantChampionship::find()
                                    ->select(['participant_championship_team_id'])
                                    ->where([
                                        'participant_championship_country_id' => $country->country_id,
                                        'participant_championship_division_id' => $division->division_id,
                                        'participant_championship_season_id' => $seasonId,
                                        'participant_championship_stage_id' => [0, Stage::FINAL_GAME],
                                    ])
                            ]
                        ])
                        ->orderBy(['championship_place' => SORT_ASC])
                        ->limit(12)
                        ->all();
                    if ($championshipArray) {
                        foreach ($championshipArray as $team) {
                            $rotateChampionship[] = $team->championship_team_id;
                        }

                        $championshipArray = Championship::find()
                            ->where([
                                'championship_division_id' => $division->division_id - 1,
                                'championship_country_id' => $country->country_id,
                                'championship_season_id' => $seasonId,
                            ])
                            ->orderBy(['championship_place' => SORT_ASC])
                            ->offset(14)
                            ->limit(2)
                            ->all();
                        foreach ($championshipArray as $team) {
                            $rotateChampionship[] = $team->championship_team_id;
                        }

                        $participantArray = ParticipantChampionship::find()
                            ->where([
                                'participant_championship_country_id' => $country->country_id,
                                'participant_championship_division_id' => $division->division_id + 1,
                                'participant_championship_season_id' => $seasonId,
                                'participant_championship_stage_id' => [0, Stage::FINAL_GAME],
                            ])
                            ->orderBy(['participant_championship_id' => SORT_ASC])
                            ->all();
                        if ($participantArray) {
                            foreach ($participantArray as $team) {
                                $rotateChampionship[] = $team->participant_championship_team_id;
                            }
                        } else {
                            $conferenceArray = Conference::find()
                                ->joinWith(['team.stadium.city.country'])
                                ->where([
                                    'conference_season_id' => $seasonId,
                                    'city_country_id' => $country->country_id
                                ])
                                ->orderBy(['conference_place' => SORT_ASC])
                                ->limit(2)
                                ->all();
                            if ($conferenceArray) {
                                foreach ($conferenceArray as $team) {
                                    $rotateChampionship[] = $team->conference_team_id;
                                }
                            } else {
                                $championshipArray = Championship::find()
                                    ->where([
                                        'championship_division_id' => $division->division_id,
                                        'championship_country_id' => $country->country_id,
                                        'championship_season_id' => $seasonId,
                                    ])
                                    ->orderBy(['championship_place' => SORT_ASC])
                                    ->offset(14)
                                    ->limit(2)
                                    ->all();
                                foreach ($championshipArray as $team) {
                                    $rotateChampionship[] = $team->championship_team_id;
                                }
                            }
                        }
                    }
                }

                if ($rotateChampionship) {
                    $rotateArray[$division->division_id] = $rotateChampionship;
                }
            }

            $rotateConference = [];

            if ($rotateArray) {
                $division = Championship::find()
                    ->where(['championship_country_id' => $country->country_id, 'championship_season_id' => $seasonId])
                    ->orderBy(['championship_division_id' => SORT_DESC])
                    ->limit(1)
                    ->one();
                $championshipArray = Championship::find()
                    ->where([
                        'championship_division_id' => $division->championship_division_id,
                        'championship_country_id' => $country->country_id,
                        'championship_season_id' => $seasonId,
                    ])
                    ->orderBy(['championship_place' => SORT_DESC])
                    ->limit(2)
                    ->all();
                foreach ($championshipArray as $team) {
                    $rotateConference[] = $team->championship_team_id;
                }

                $conferenceArray = Conference::find()
                    ->joinWith(['team.stadium.city.country'])
                    ->where(['conference_season_id' => $seasonId, 'city_country_id' => $country->country_id])
                    ->orderBy(['conference_place' => SORT_ASC])
                    ->offset(2)
                    ->limit(9999)
                    ->all();
                foreach ($conferenceArray as $team) {
                    $rotateConference[] = $team->conference_team_id;
                }
            } else {
                $conferenceArray = Conference::find()
                    ->joinWith(['team.stadium.city.country'])
                    ->where(['conference_season_id' => $seasonId, 'city_country_id' => $country->country_id])
                    ->orderBy(['conference_place' => SORT_ASC])
                    ->all();
                foreach ($conferenceArray as $team) {
                    $rotateConference[] = $team->conference_team_id;
                }
            }

            $rotateArray['conference'] = $rotateConference;

            if (count($rotateArray) < 5 && count($rotateArray['conference']) >= 16) {
                foreach ($divisionArray as $item) {
                    if (!isset($rotateArray[$item->division_id]) && count($rotateArray['conference']) >= 16) {
                        $rotateArray[$item->division_id] = array_slice($rotateArray['conference'], 0, 16);
                        array_splice($rotateArray['conference'], 0, 16);
                    }
                }
            }

            foreach ($divisionArray as $division) {
                if (isset($rotateArray[$division->division_id])) {
                    $data = [];

                    foreach ($rotateArray[$division->division_id] as $item) {
                        $data[] = [$country->country_id, $division->division_id, $seasonId + 1, $item];
                    }

                    Yii::$app->db
                        ->createCommand()
                        ->batchInsert(
                            Championship::tableName(),
                            [
                                'championship_country_id',
                                'championship_division_id',
                                'championship_season_id',
                                'championship_team_id'
                            ],
                            $data
                        )
                        ->execute();
                }
            }
        }
    }
}
