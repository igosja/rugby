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
     */
    public function execute()
    {
        $seasonId = Season::getCurrentSeason() + 1;
        $teamArray = Team::find()
            ->where([
                'not',
                [
                    'team_id' => Championship::find()
                        ->select(['championship_team_id'])
                        ->where(['championship_season_id' => $seasonId])
                ]
            ])
            ->andWhere(['!=', 'team_id', 0])
            ->orderBy('RAND()')
            ->each();

        $data = [];
        foreach ($teamArray as $team) {
            /**
             * @var Team $team
             */
            $data[] = [$seasonId, $team->team_id];
        }

        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                Conference::tableName(),
                ['conference_season_id', 'conference_team_id'],
                $data
            )
            ->execute();

        $scheduleId = Schedule::find()
            ->select(['schedule_id'])
            ->where([
                'schedule_tournament_type_id' => TournamentType::CONFERENCE,
                'schedule_stage_id' => Stage::TOUR_1,
                'schedule_season_id' => $seasonId,
            ])
            ->limit(1)
            ->scalar();

        /** @var Conference[] $conferenceArray */
        $conferenceArray = Conference::find()
            ->with(['team'])
            ->where(['conference_season_id' => $seasonId])
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
            if (!isset($conferenceArray[$item[0]]) || !isset($conferenceArray[$item[1]])) {
                continue;
            }

            $data[] = [
                $conferenceArray[$item[1]]->conference_team_id,
                $conferenceArray[$item[0]]->conference_team_id,
                $scheduleId,
                $conferenceArray[$item[0]]->team->team_stadium_id,
            ];
        }

        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                Game::tableName(),
                ['game_guest_team_id', 'game_home_team_id', 'game_schedule_id', 'game_stadium_id'],
                $data
            )
            ->execute();
    }
}
