<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\Championship;
use common\models\Division;
use common\models\LeagueDistribution;
use common\models\ParticipantChampionship;
use common\models\ParticipantLeague;
use common\models\Season;
use common\models\Stage;
use Yii;
use yii\db\Exception;

/**
 * Class InsertLeagueParticipant
 * @package console\models\newSeason
 */
class InsertLeagueParticipant
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $seasonId = Season::getCurrentSeason();

        $data = [];

        $distributionArray = LeagueDistribution::find()
            ->where(['league_distribution_season_id' => $seasonId + 1])
            ->orderBy(['league_distribution_id' => SORT_ASC])
            ->all();
        foreach ($distributionArray as $distribution) {
            $participantArray = [];

            $distributionTotal = $distribution->league_distribution_group + $distribution->league_distribution_qualification_3 + $distribution->league_distribution_qualification_2 + $distribution->league_distribution_qualification_1;

            $participantChampionship = ParticipantChampionship::find()
                ->where([
                    'participant_championship_country_id' => $distribution->league_distribution_country_id,
                    'participant_championship_division_id' => Division::D1,
                    'participant_championship_season_id' => $seasonId,
                    'participant_championship_stage_id' => 0,
                ])
                ->limit(1)
                ->one();

            $participantArray[] = $participantChampionship->participant_championship_team_id;

            if ($distributionTotal > 1) {
                $championship = Championship::find()
                    ->where([
                        'championship_country_id' => $distribution->league_distribution_country_id,
                        'championship_division_id' => Division::D1,
                        'championship_season_id' => $seasonId,
                        'championship_place' => 1,
                    ])
                    ->andWhere(['not', ['championship_team_id' => $participantArray]])
                    ->limit(1)
                    ->one();
                if ($championship) {
                    $participantArray[] = $championship->championship_team_id;
                } else {
                    $participantChampionship = ParticipantChampionship::find()
                        ->where([
                            'participant_championship_country_id' => $distribution->league_distribution_country_id,
                            'participant_championship_division_id' => Division::D1,
                            'participant_championship_season_id' => $seasonId,
                            'participant_championship_stage_id' => Stage::FINAL_GAME,
                        ])
                        ->andWhere(['not', ['participant_championship_team_id' => $participantArray]])
                        ->limit(1)
                        ->one();
                    $participantArray[] = $participantChampionship->participant_championship_team_id;
                }
            }

            if ($distributionTotal > 2) {
                $participantChampionship = ParticipantChampionship::find()
                    ->where([
                        'participant_championship_country_id' => $distribution->league_distribution_country_id,
                        'participant_championship_division_id' => Division::D1,
                        'participant_championship_season_id' => $seasonId,
                        'participant_championship_stage_id' => Stage::FINAL_GAME,
                    ])
                    ->andWhere(['not', ['participant_championship_team_id' => $participantArray]])
                    ->limit(1)
                    ->one();
                if ($participantChampionship) {
                    $participantArray[] = $participantChampionship->participant_championship_team_id;
                } else {
                    $championship = Championship::find()
                        ->where([
                            'championship_country_id' => $distribution->league_distribution_country_id,
                            'championship_division_id' => Division::D1,
                            'championship_season_id' => $seasonId,
                        ])
                        ->andWhere(['not', ['championship_team_id' => $participantArray]])
                        ->orderBy(['championship_place' => SORT_ASC])
                        ->limit(1)
                        ->one();
                    $participantArray[] = $championship->championship_team_id;
                }
            }

            if ($distributionTotal > 3) {
                $championshipArray = Championship::find()
                    ->where([
                        'championship_country_id' => $distribution->league_distribution_country_id,
                        'championship_division_id' => Division::D1,
                        'championship_season_id' => $seasonId,
                    ])
                    ->andWhere(['not', ['championship_team_id' => $participantArray]])
                    ->orderBy(['championship_place' => SORT_ASC])
                    ->limit($distributionTotal - count($participantArray))
                    ->all();
                foreach ($championshipArray as $championship) {
                    $participantArray[] = $championship->championship_team_id;
                }
            }

            if ($distribution->league_distribution_group) {
                $groupParticipantArray = array_slice($participantArray, 0, $distribution->league_distribution_group);
                array_splice($participantArray, 0, $distribution->league_distribution_group);

                foreach ($groupParticipantArray as $item) {
                    $data[] = [$seasonId + 1, Stage::TOUR_LEAGUE_1, $item];
                }
            }

            for ($i = 3; $i >= 1; $i--) {
                $qualify = 'league_distribution_qualification_' . $i;
                if (0 != $distribution->$qualify) {
                    if (3 == $i) {
                        $stage = Stage::QUALIFY_3;
                    } elseif (2 == $i) {
                        $stage = Stage::QUALIFY_2;
                    } else {
                        $stage = Stage::QUALIFY_1;
                    }

                    $qualifyArray = array_slice($participantArray, 0, $distribution->$qualify);
                    array_splice($participantArray, 0, $distribution->$qualify);

                    foreach ($qualifyArray as $item) {
                        $data[] = [$seasonId + 1, $stage, $item];
                    }
                }
            }
        }

        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                ParticipantLeague::tableName(),
                [
                    'participant_league_season_id',
                    'participant_league_stage_in',
                    'participant_league_team_id',
                ],
                $data
            )
            ->execute();
    }
}
