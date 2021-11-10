<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Championship;
use common\models\db\Conference;
use common\models\db\Game;
use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\Stage;
use common\models\db\Team;
use common\models\db\TournamentType;
use common\models\db\Weather;
use Yii;
use yii\db\Exception;

/**
 * Class InsertConference
 * @package console\models\newSeason
 */
class InsertConference
{
    /**
     * @return void
     * @throws Exception
     * @throws \Exception
     */
    public function execute(): void
    {
        $seasonId = Season::getCurrentSeason() + 1;
        $teamArray = Team::find()
            ->where([
                'not',
                [
                    'id' => Championship::find()
                        ->select(['team_id'])
                        ->where(['season_id' => $seasonId])
                ]
            ])
            ->andWhere(['!=', 'id', 0])
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
                Conference::tableName(),
                ['season_id', 'team_id'],
                $data
            )
            ->execute();

        $scheduleId = Schedule::find()
            ->select(['id'])
            ->where([
                'tournament_type_id' => TournamentType::CONFERENCE,
                'stage_id' => Stage::TOUR_1,
                'season_id' => $seasonId,
            ])
            ->limit(1)
            ->scalar();

        /** @var Conference[] $conferenceArray */
        $conferenceArray = Conference::find()
            ->with(['team'])
            ->where(['season_id' => $seasonId])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $stage = Stage::TOUR_1 - 1;
        $countTeam = count($conferenceArray);

        $keyArray = [];

        for ($one = 0, $two = $stage; $one < $two; $one++, $two--) {
            $keyArray[] = [$one, $two];
        }

        for ($one = $countTeam - 2, $two = $stage + 1; $one > $two + 1; $one--, $two++) {
            $keyArray[] = [$one, $two];
        }

        if ($countTeam / 2 + ($stage - 1) / 2 != $countTeam - 1) {
            $keyArray[] = [$countTeam / 2 + ($stage - 1) / 2, $countTeam - 1];
        }

        $data = [];
        foreach ($keyArray as $item) {
            if (!isset($conferenceArray[$item[0]], $conferenceArray[$item[1]])) {
                continue;
            }

            $data[] = [
                $conferenceArray[$item[1]]->team_id,
                $conferenceArray[$item[0]]->team_id,
                $scheduleId,
                $conferenceArray[$item[0]]->team->stadium_id,
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
