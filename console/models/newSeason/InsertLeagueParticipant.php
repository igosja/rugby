<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Championship;
use common\models\db\Division;
use common\models\db\LeagueDistribution;
use common\models\db\ParticipantLeague;
use common\models\db\Season;
use common\models\db\Stage;
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
    public function execute(): void
    {
        $seasonId = Season::getCurrentSeason();

        $data = [];

        $distributionArray = LeagueDistribution::find()
            ->where(['season_id' => $seasonId + 1])
            ->orderBy(['id' => SORT_ASC])
            ->all();
        foreach ($distributionArray as $distribution) {
            $participantArray = [];

            $distributionTotal = $distribution->group + $distribution->qualification_3 + $distribution->qualification_2 + $distribution->qualification_1;

            for ($i = 1; $i <= $distributionTotal; $i++) {
                $championship = Championship::find()
                    ->where([
                        'federation_id' => $distribution->federation_id,
                        'division_id' => Division::D1,
                        'season_id' => $seasonId,
                    ])
                    ->andWhere(['not', ['team_id' => $participantArray]])
                    ->orderBy(['place' => SORT_ASC])
                    ->limit(1)
                    ->one();
                if ($championship) {
                    $participantArray[] = $championship->team_id;
                }
            }

            if ($distribution->group) {
                $groupParticipantArray = array_slice($participantArray, 0, $distribution->group);
                array_splice($participantArray, 0, $distribution->group);

                foreach ($groupParticipantArray as $item) {
                    $data[] = [$seasonId + 1, Stage::TOUR_LEAGUE_1, $item];
                }
            }

            for ($i = 3; $i >= 1; $i--) {
                $qualify = 'qualification_' . $i;
                if (0 !== $distribution->$qualify) {
                    if (3 === $i) {
                        $stage = Stage::QUALIFY_3;
                    } elseif (2 === $i) {
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
                    'season_id',
                    'stage_in_id',
                    'team_id',
                ],
                $data
            )
            ->execute();
    }
}
