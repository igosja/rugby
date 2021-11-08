<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Championship;
use common\models\db\Conference;
use common\models\db\Division;
use common\models\db\Federation;
use common\models\db\ParticipantChampionship;
use common\models\db\Season;
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
    public function execute(): void
    {
        $seasonId = Season::getCurrentSeason();

        $divisionArray = Division::find()
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $federationArray = Federation::find()
            ->joinWith(['country.city'])
            ->where(['!=', 'city.id', 0])
            ->groupBy(['federation.id'])
            ->orderBy(['federation.id' => SORT_ASC])
            ->all();
        foreach ($federationArray as $federation) {
            $rotateArray = [];

            foreach ($divisionArray as $division) {
                $rotateChampionship = [];

                $championshipArray = Championship::find()
                    ->where([
                        'division_id' => $division->id - 1,
                        'federation_id' => $federation->id,
                        'season_id' => $seasonId,
                    ])
                    ->orderBy(['place' => SORT_ASC])
                    ->offset(14)
                    ->limit(2)
                    ->all();
                if (!$championshipArray) {
                    $championshipArray = Championship::find()
                        ->where([
                            'division_id' => $division->id,
                            'federation_id' => $federation->id,
                            'season_id' => $seasonId,
                        ])
                        ->orderBy(['place' => SORT_ASC])
                        ->limit(2)
                        ->all();
                }
                foreach ($championshipArray as $team) {
                    $rotateChampionship[] = $team->team_id;
                }

                $championshipArray = Championship::find()
                    ->where([
                        'division_id' => $division->id,
                        'federation_id' => $federation->id,
                        'season_id' => $seasonId,
                    ])
                    ->orderBy(['place' => SORT_ASC])
                    ->offset(2)
                    ->limit(12)
                    ->all();
                foreach ($championshipArray as $team) {
                    $rotateChampionship[] = $team->team_id;
                }

                $championshipArray = Championship::find()
                    ->where([
                        'division_id' => $division->id + 1,
                        'federation_id' => $federation->id,
                        'season_id' => $seasonId,
                    ])
                    ->orderBy(['place' => SORT_ASC])
                    ->limit(2)
                    ->all();
                if ($championshipArray) {
                    foreach ($championshipArray as $team) {
                        $rotateChampionship[] = $team->team_id;
                    }
                } else {
                    $conferenceArray = Conference::find()
                        ->joinWith(['team.stadium.city.country'])
                        ->where([
                            'season_id' => $seasonId,
                            'country_id' => $federation->country_id
                        ])
                        ->orderBy(['place' => SORT_ASC])
                        ->limit(2)
                        ->all();
                    if ($conferenceArray) {
                        foreach ($conferenceArray as $team) {
                            $rotateChampionship[] = $team->team_id;
                        }
                    } else {
                        $championshipArray = Championship::find()
                            ->where([
                                'division_id' => $division->id,
                                'federation_id' => $federation->id,
                                'season_id' => $seasonId,
                            ])
                            ->orderBy(['place' => SORT_ASC])
                            ->offset(14)
                            ->limit(2)
                            ->all();
                        foreach ($championshipArray as $team) {
                            $rotateChampionship[] = $team->team_id;
                        }
                    }
                }

                if ($rotateChampionship) {
                    $rotateArray[$division->id] = $rotateChampionship;
                }
            }

            $rotateConference = [];

            if ($rotateArray) {
                $division = Championship::find()
                    ->where(['federation_id' => $federation->id, 'season_id' => $seasonId])
                    ->orderBy(['division_id' => SORT_DESC])
                    ->limit(1)
                    ->one();
                $championshipArray = Championship::find()
                    ->where([
                        'division_id' => $division->division_id,
                        'federation_id' => $federation->id,
                        'season_id' => $seasonId,
                    ])
                    ->orderBy(['place' => SORT_DESC])
                    ->limit(2)
                    ->all();
                foreach ($championshipArray as $team) {
                    $rotateConference[] = $team->team_id;
                }

                $conferenceArray = Conference::find()
                    ->joinWith(['team.stadium.city.country'])
                    ->where(['season_id' => $seasonId, 'country_id' => $federation->country_id])
                    ->orderBy(['place' => SORT_ASC])
                    ->offset(2)
                    ->limit(9999)
                    ->all();
                foreach ($conferenceArray as $team) {
                    $rotateConference[] = $team->team_id;
                }
            } else {
                $conferenceArray = Conference::find()
                    ->joinWith(['team.stadium.city.country'])
                    ->where(['season_id' => $seasonId, 'country_id' => $federation->country_id])
                    ->orderBy(['place' => SORT_ASC])
                    ->all();
                foreach ($conferenceArray as $team) {
                    $rotateConference[] = $team->team_id;
                }
            }

            $rotateArray['conference'] = $rotateConference;

            if (count($rotateArray) < 5 && count($rotateArray['conference']) >= 16) {
                foreach ($divisionArray as $item) {
                    if (!isset($rotateArray[$item->id])) {
                        $rotateArray[$item->id] = array_slice($rotateArray['conference'], 0, 16);
                        array_splice($rotateArray['conference'], 0, 16);
                    }
                }
            }

            foreach ($divisionArray as $division) {
                if (isset($rotateArray[$division->id])) {
                    $data = [];

                    foreach ($rotateArray[$division->id] as $item) {
                        $data[] = [$federation->id, $division->id, $seasonId + 1, $item];
                    }

                    Yii::$app->db
                        ->createCommand()
                        ->batchInsert(
                            Championship::tableName(),
                            [
                                'federation_id',
                                'division_id',
                                'season_id',
                                'team_id'
                            ],
                            $data
                        )
                        ->execute();
                }
            }
        }
    }
}
