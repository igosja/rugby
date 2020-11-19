<?php

namespace console\models\newSeason;

use common\models\Game;
use common\models\ParticipantLeague;
use common\models\Schedule;
use common\models\Season;
use common\models\Stage;
use common\models\TournamentType;
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
    public function execute()
    {
        $seasonId = Season::getCurrentSeason() + 1;

        $teamArray = $this->lot(Stage::QUALIFY_1);

        $stageArray = Schedule::find()
            ->where([
                'schedule_season_id' => $seasonId,
                'schedule_stage_id' => Stage::QUALIFY_1,
                'schedule_tournament_type_id' => TournamentType::LEAGUE,
            ])
            ->orderBy(['schedule_id' => SORT_ASC])
            ->limit(2)
            ->all();

        foreach ($teamArray as $item) {
            $model = new Game();
            $model->game_guest_team_id = $item['guest'];
            $model->game_home_team_id = $item['home'];
            $model->game_schedule_id = $stageArray[0]->schedule_id;
            $model->save();

            $model = new Game();
            $model->game_guest_team_id = $item['home'];
            $model->game_home_team_id = $item['guest'];
            $model->game_schedule_id = $stageArray[1]->schedule_id;
            $model->save();
        }

        $sql = "UPDATE `game`
                LEFT JOIN `team`
                ON `game_home_team_id`=`team_id`
                SET `game_stadium_id`=`team_stadium_id`
                WHERE `game_schedule_id` IN (" . $stageArray[0]->schedule_id . ", " . $stageArray[1]->schedule_id . ")";
        Yii::$app->db->createCommand($sql)->execute();
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
        $seasonId = Season::getCurrentSeason() + 1;

        $participantLeagueArray = ParticipantLeague::find()
            ->joinwith(['team'])
            ->where([
                'participant_league_season_id' => $seasonId,
                'participant_league_stage_id' => 0,
                'participant_league_stage_in' => [Stage::QUALIFY_1],
            ])
            ->orderBy(['team_power_vs' => SORT_DESC])
            ->all();

        $teamResultArray = [[], []];

        $countParticipantLeague = count($participantLeagueArray);
        $limitHalf = $countParticipantLeague / 2;

        for ($i = 0; $i < $countParticipantLeague; $i++) {
            if ($i < $limitHalf) {
                $teamResultArray[0][] = $participantLeagueArray[$i];
            } else {
                $teamResultArray[1][] = $participantLeagueArray[$i];
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
            'team_id' => $teamArray[0][$team]->participant_league_team_id,
            'country_id' => $teamArray[0][$team]->team->stadium->city->city_country_id,
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
            if ($item->team->stadium->city->city_country_id != $homeTeam['country_id']) {
                for ($i = 0, $countTeam = count($teamArray[1]); $i < $countTeam; $i++) {
                    if ($teamArray[1][$i]->participant_league_team_id == $item->participant_league_team_id) {
                        return [
                            'i' => $i,
                            'team_id' => $teamArray[1][$i]->participant_league_team_id,
                        ];
                    }
                }
            }
        }

        return [];
    }
}
