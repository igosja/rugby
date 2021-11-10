<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\Swiss;
use common\models\db\Team;
use common\models\db\TournamentType;
use common\models\db\Weather;
use Yii;
use yii\db\Exception;

/**
 * Class InsertSwiss
 * @package console\models\generator
 */
class InsertSwiss
{
    /**
     * @return void
     * @throws Exception
     * @throws \Exception
     */
    public function execute(): void
    {
        /**
         * @var Schedule $schedule
         */
        $schedule = Schedule::find()
            ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ->andWhere(['tournament_type_id' => [TournamentType::CONFERENCE, TournamentType::OFF_SEASON]])
            ->limit(1)
            ->one();
        if (!$schedule) {
            return;
        }

        $schedule = Schedule::find()
            ->where(['>', 'id', $schedule->id])
            ->andWhere(['tournament_type_id' => $schedule->tournament_type_id])
            ->orderBy(['id' => SORT_ASC])
            ->limit(1)
            ->one();

        if (!$schedule) {
            return;
        }

        $gameArray = $this->swissGame($schedule->tournament_type_id, $schedule->stage_id);

        $data = [];

        foreach ($gameArray as $item) {
            $data[] = [$item['guest'], $item['home'], $schedule->id, Weather::getRandomWeatherId()];
        }

        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                Game::tableName(),
                ['guest_team_id', 'home_team_id', 'schedule_id', 'weather_id'],
                $data
            )
            ->execute();

        $sql = "UPDATE `game`
                LEFT JOIN `team`
                ON `home_team_id`=`team`.`id`
                SET `game`.`stadium_id`=`team`.`stadium_id`
                WHERE `game`.`schedule_id`=" . $schedule->id;
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * @param int $tournamentTypeId
     * @param int $stageId
     * @return array
     * @throws Exception
     */
    private function swissGame(int $tournamentTypeId, int $stageId): array
    {
        $positionDifference = 1;

        $teamArray = $this->swissPrepare($tournamentTypeId);
        return $this->swiss($tournamentTypeId, $positionDifference, $teamArray, $stageId);
    }

    /**
     * @param int $tournamentTypeId
     * @return Swiss[]
     * @throws Exception
     */
    private function swissPrepare(int $tournamentTypeId): array
    {
        Yii::$app->db->createCommand()->truncateTable(Swiss::tableName())->execute();

        if (TournamentType::OFF_SEASON === $tournamentTypeId) {
            $sql = "INSERT INTO `swiss` (`guest`, `home`, `place`, `team_id`)
                    SELECT `guest`, `home`, `place`, `team_id`
                    FROM `off_season`
                    WHERE `season_id`=" . Season::getCurrentSeason() . "
                    ORDER BY `id`";
            Yii::$app->db->createCommand($sql)->execute();
        } else {
            $sql = "INSERT INTO `swiss` (`guest`, `home`, `place`, `team_id`)
                    SELECT `guest`, `home`, `place`, `team_id`
                    FROM `conference`
                    WHERE `season_id`=" . Season::getCurrentSeason() . "
                    ORDER BY `id`";
            Yii::$app->db->createCommand($sql)->execute();
        }

        /**
         * @var Swiss[] $teamArray
         */
        $teamArray = Swiss::find()
            ->with(['team'])
            ->orderBy(['id' => SORT_ASC])
            ->all();
        $maxCount = 1;

        foreach ($teamArray as $iValue) {
            $userTeamSubQuery = null;
            $subQuery = Game::find()
                ->select('IF(`home_team_id`=' . $iValue->team_id . ', `guest_team_id`, `home_team_id`) AS `home_team_id`')
                ->joinWith(['schedule'])
                ->where([
                    'or',
                    ['home_team_id' => $iValue->team_id],
                    ['guest_team_id' => $iValue->team_id]
                ])
                ->andWhere([
                    'tournament_type_id' => $tournamentTypeId,
                    'season_id' => Season::getCurrentSeason()
                ])
                ->groupBy(['home_team_id'])
                ->having(['>=', 'COUNT(`game`.`id`)', $maxCount]);
            if ($iValue->team->user_id) {
                $userTeamSubQuery = Team::find()
                    ->select(['id'])
                    ->where(['user_id' => $iValue->team->user_id]);
            }
            $free = Swiss::find()
                ->select(['team_id'])
                ->where(['!=', 'team_id', $iValue->team_id])
                ->andWhere(['not', ['team_id' => $subQuery]])
                ->andFilterWhere(['not', ['team_id' => $userTeamSubQuery]])
                ->orderBy(['swiss.id' => SORT_ASC])
                ->column();
            foreach ($free as $key => $value) {
                $free[$key] = (int)$value;
            }

            $iValue->opponent = $free;
        }

        return $teamArray;
    }

    /**
     * @param int $tournamentTypeId
     * @param int $positionDifference
     * @param Swiss[] $teamArray
     * @param int $stageId
     * @return array
     */
    private function swiss(int $tournamentTypeId, int $positionDifference, array $teamArray, int $stageId): array
    {
        if (TournamentType::OFF_SEASON === $tournamentTypeId) {
            $gameArray = $this->swissOffseason($teamArray, $stageId);
        } else {
            $gameArray = $this->swissConference($teamArray, $stageId);
        }

        return $gameArray;
    }

    /**
     * @param int $tournamentTypeId
     * @param int $difference
     * @param Swiss[] $teamArray
     * @param array $gameArray
     * @return array
     */
    private function swissOne(int $tournamentTypeId, int $difference, array $teamArray, array $gameArray = []): array
    {
        $homeTeam = $this->getSwissHomeTeam($teamArray);
        $guestTeam = $this->getSwissGuestTeam($teamArray, $homeTeam, $difference);

        if (!$homeTeam || !$guestTeam) {
            return [];
        }

        $gameArray[] = [
            'home' => $homeTeam['team_id'],
            'guest' => $guestTeam['team_id']
        ];

        unset($teamArray[$homeTeam['i']], $teamArray[$guestTeam['i']]);

        $teamArray = array_values($teamArray);

        if (count($teamArray)) {
            $gameArray = $this->swissOne($tournamentTypeId, $difference, $teamArray, $gameArray);
        }

        return $gameArray;
    }

    /**
     * @param Swiss[] $teamArray
     * @return array
     */
    private function getSwissHomeTeam(array $teamArray): array
    {
        for ($k = 0; $k <= 10; $k++) {
            foreach ($teamArray as $i => $swiss) {
                if ($swiss->home <= $swiss->guest + $k) {
                    return [
                        'i' => $i,
                        'team_id' => $swiss->team_id,
                        'stadium_id' => $swiss->team->stadium_id,
                        'place' => $swiss->place,
                        'opponent' => $swiss['opponent'],
                    ];
                }
            }
        }

        return [];
    }

    /**
     * @param Swiss[] $teamArray
     * @param array $homeTeam
     * @param int $positionDifference
     * @return array
     */
    private function getSwissGuestTeam(array $teamArray, array $homeTeam, int $positionDifference): array
    {
        for ($k = 0; $k <= 10; $k++) {
            foreach ($teamArray as $i => $swiss) {
                if (
                    $swiss->home + $k >= $swiss->guest
                    && $swiss->place >= $homeTeam['place'] - $positionDifference
                    && $swiss->place <= $homeTeam['place'] + $positionDifference
                    && $swiss->team_id !== $homeTeam['team_id']
                    && in_array($homeTeam['team_id'], $swiss['opponent'], true)
                    && in_array($swiss->team_id, $homeTeam['opponent'], true)
                ) {
                    return [
                        'i' => $i,
                        'team_id' => $swiss->team_id,
                    ];
                }
            }
        }

        return [];
    }

    /**
     * @param Swiss[] $teamArray
     * @param int $stageId
     * @return array
     */
    private function swissOffseason(array $teamArray, int $stageId): array
    {
        $stage = $stageId - 1;
        $countTeam = count($teamArray);

        $scheme = 1;
        if (0 === $stage % 2) {
            $scheme = 2;
        }

        $keyArray = [];

        if (1 === $scheme) {
            for ($one = 0, $two = $stage; $one < $two; $one++, $two--) {
                $keyArray[] = [$one, $two];
            }

            for ($one = $countTeam - 2, $two = $stage + 1; $one > $two + 1; $one--, $two++) {
                $keyArray[] = [$one, $two];
            }

            if ($countTeam / 2 + ($stage - 1) / 2 !== $countTeam - 1) {
                $keyArray[] = [$countTeam / 2 + ($stage - 1) / 2, $countTeam - 1];
            }
        } else {
            for ($one = $stage, $two = 0; $one > $two + 1; $one--, $two++) {
                $keyArray[] = [$one, $two];
            }

            for ($one = $stage + 1, $two = $countTeam - 2; $one < $two; $one++, $two--) {
                $keyArray[] = [$one, $two];
            }

            if ($stage / 2 !== $countTeam - 1) {
                $keyArray[] = [$stage / 2, $countTeam - 1];
            }
        }

        $gameArray = [];

        foreach ($keyArray as $item) {
            $gameArray[] = [
                'home' => $teamArray[$item[0]]->team_id,
                'guest' => $teamArray[$item[1]]->team_id,
            ];
        }

        return $gameArray;
    }

    /**
     * @param Swiss[] $teamArray
     * @param int $stageId
     * @return array
     */
    private function swissConference(array $teamArray, int $stageId): array
    {
        $stage = $stageId - 1;
        $countTeam = count($teamArray);

        $scheme = 1;
        if (0 === $stage % 2) {
            $scheme = 2;
        }

        $keyArray = [];

        if (1 === $scheme) {
            for ($one = 0, $two = $stage; $one < $two; $one++, $two--) {
                $keyArray[] = [$one, $two];
            }

            for ($one = $countTeam - 2, $two = $stage + 1; $one > $two + 1; $one--, $two++) {
                $keyArray[] = [$one, $two];
            }

            if ($countTeam / 2 + ($stage - 1) / 2 !== $countTeam - 1) {
                $keyArray[] = [$countTeam / 2 + ($stage - 1) / 2, $countTeam - 1];
            }
        } else {
            for ($one = $stage, $two = 0; $one > $two + 1; $one--, $two++) {
                $keyArray[] = [$one, $two];
            }

            for ($one = $stage + 1, $two = $countTeam - 2; $one < $two; $one++, $two--) {
                $keyArray[] = [$one, $two];
            }

            if ($stage / 2 !== $countTeam - 1) {
                $keyArray[] = [$stage / 2, $countTeam - 1];
            }
        }

        $gameArray = [];

        foreach ($keyArray as $item) {
            $gameArray[] = [
                'home' => $teamArray[$item[0]]->team_id,
                'guest' => $teamArray[$item[1]]->team_id,
            ];
        }

        return $gameArray;
    }
}
