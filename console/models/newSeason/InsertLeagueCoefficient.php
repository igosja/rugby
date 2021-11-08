<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\LeagueCoefficient;
use common\models\db\ParticipantLeague;
use common\models\db\Season;
use Yii;
use yii\db\Exception;

/**
 * Class InsertLeagueCoefficient
 * @package console\models\newSeason
 */
class InsertLeagueCoefficient
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $seasonId = Season::getCurrentSeason() + 1;

        $participantLeagueArray = ParticipantLeague::find()
            ->where(['season_id' => $seasonId])
            ->orderBy(['id' => SORT_ASC])
            ->each();

        $data = [];
        foreach ($participantLeagueArray as $participantLeague) {
            /**
             * @var ParticipantLeague $participantLeague
             */
            $data[] = [
                $participantLeague->team->stadium->city->country->federation->id,
                $seasonId,
                $participantLeague->team_id,
            ];
        }

        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                LeagueCoefficient::tableName(),
                [
                    'federation_id',
                    'season_id',
                    'team_id',
                ],
                $data
            )
            ->execute();
    }
}
