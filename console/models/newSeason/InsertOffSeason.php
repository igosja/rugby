<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Game;
use common\models\db\OffSeason;
use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\Stage;
use common\models\db\Team;
use common\models\db\TournamentType;
use common\models\db\Weather;
use Exception;
use Yii;

/**
 * Class InsertOffSeason
 * @package console\models\newSeason
 */
class InsertOffSeason
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $seasonId = Season::getCurrentSeason() + 1;

        $teamArray = Team::find()
            ->where(['!=', 'id', 0])
            ->orderBy('RAND()')
            ->each();

        $data = [];
        foreach ($teamArray as $team) {
            /**
             * @var Team $team
             */
            $data[] = [$seasonId, $team->id];
        }

        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                OffSeason::tableName(),
                ['season_id', 'team_id'],
                $data
            )
            ->execute();

        $scheduleId = Schedule::find()
            ->select(['id'])
            ->where([
                'tournament_type_id' => TournamentType::OFF_SEASON,
                'stage_id' => Stage::TOUR_1,
                'season_id' => $seasonId,
            ])
            ->limit(1)
            ->scalar();

        /** @var OffSeason[] $offSeasonArray */
        $offSeasonArray = OffSeason::find()
            ->with(['team.stadium'])
            ->where(['season_id' => $seasonId])
            ->orderBy(['id' => SORT_ASC])
            ->all();
        $countOffSeason = count($offSeasonArray);

        $data = [];
        for ($i = 0; $i < $countOffSeason; $i += 2) {
            $data[] = [
                $offSeasonArray[$i]->team_id,
                $offSeasonArray[$i + 1]->team_id,
                $scheduleId,
                $offSeasonArray[$i + 1]->team->stadium_id,
                Weather::getRandomWeatherId()
            ];
        }

        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                Game::tableName(),
                ['guest_team_id', 'home_team_id', 'schedule_id', 'stadium_id', 'weather_id'],
                $data
            )
            ->execute();
    }
}