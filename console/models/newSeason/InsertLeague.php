<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Game;
use common\models\db\ParticipantLeague;
use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\Stage;
use common\models\db\TournamentType;
use common\models\db\Weather;
use Exception;
use Yii;

/**
 * Class InsertLeague
 * @package console\models\newSeason
 */
class InsertLeague
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $seasonId = Season::getCurrentSeason() + 1;

        $teamArray = $this->lot();

        $stageArray = Schedule::find()
            ->where([
                'season_id' => $seasonId,
                'stage_id' => Stage::QUALIFY_1,
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

    /**
     * @return array
     */
    private function lot(): array
    {
        $teamArray = $this->prepare();
        return $this->all($teamArray, Stage::QUALIFY_1);
    }

    /**
     * @return array
     */
    private function prepare(): array
    {
        $seasonId = Season::getCurrentSeason() + 1;

        $participantLeagueArray = ParticipantLeague::find()
            ->joinwith(['team'])
            ->where([
                'season_id' => $seasonId,
                'stage_out_id' => null,
                'stage_in_id' => [Stage::QUALIFY_1],
            ])
            ->orderBy(['power_vs' => SORT_DESC])
            ->all();

        $teamResultArray = [[], []];

        $countParticipantLeague = count($participantLeagueArray);
        $limitHalf = $countParticipantLeague / 2;

        foreach ($participantLeagueArray as $i => $participantLeague) {
            if ($i < $limitHalf) {
                $teamResultArray[0][] = $participantLeague;
            } else {
                $teamResultArray[1][] = $participantLeague;
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
        if (!$team_result_array = $this->one($teamArray)) {
            $team_result_array = $this->all($teamArray, $stageId);
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

        unset($teamArray[0][$homeTeam['i']], $teamArray[1][$guestTeam['i']]);

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
     * @param ParticipantLeague[][] $teamArray
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
     * @param ParticipantLeague[][] $teamArray
     * @param array $homeTeam
     * @return array
     */
    private function teamGuest(array $teamArray, array $homeTeam): array
    {
        $shuffleArray = $teamArray[1];

        shuffle($shuffleArray);

        foreach ($shuffleArray as $item) {
            if ($item->team->stadium->city->country_id !== $homeTeam['country_id']) {
                foreach ($teamArray[1] as $i => $team) {
                    if ($team->team_id === $item->team_id) {
                        return [
                            'i' => $i,
                            'team_id' => $team->team_id,
                        ];
                    }
                }
            }
        }

        return [];
    }
}
