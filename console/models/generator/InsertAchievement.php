<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\Stage;
use common\models\db\TournamentType;
use Yii;
use yii\db\Exception;

/**
 * Class InsertAchievement
 * @package console\models\generator
 */
class InsertAchievement
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        /**
         * @var Schedule[] $scheduleArray
         */
        $scheduleArray = Schedule::find()
            ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ->all();

        $seasonId = Season::getCurrentSeason();

        foreach ($scheduleArray as $schedule) {
            if (TournamentType::OFF_SEASON === $schedule->tournament_type_id && Stage::TOUR_12 === $schedule->stage_id) {
                $sql = "INSERT INTO `achievement` (`place`, `season_id`, `team_id`, `tournament_type_id`, `user_id`)
                        SELECT `place`, `season_id`, `team_id`, " . TournamentType::OFF_SEASON . ", `user_id`
                        FROM `off_season`
                        LEFT JOIN `team`
                        ON `team_id`=`team`.`id`
                        WHERE `season_id`=$seasonId";
                Yii::$app->db->createCommand($sql)->execute();

                $sql = "INSERT INTO `achievement_player` (`player_id`, `place`, `season_id`, `team_id`, `tournament_type_id`)
                        SELECT `player`.`id`, `place`, `season_id`, `team`.`id`, " . TournamentType::OFF_SEASON . "
                        FROM `off_season`
                        LEFT JOIN `team`
                        ON `team_id`=`team_id`
                        LEFT JOIN `player`
                        ON `team`.`id`=`player`.`team_id`
                        WHERE `season_id`=$seasonId";
                Yii::$app->db->createCommand($sql)->execute();
            } elseif (TournamentType::NATIONAL === $schedule->tournament_type_id && Stage::TOUR_11 === $schedule->stage_id) {
                $sql = "INSERT INTO `achievement` (`place`, `division_id`, `season_id`, `national_id`, `tournament_type_id`, `user_id`)
                        SELECT `place`, `division_id`, `season_id`, `national_id`, " . TournamentType::NATIONAL . ", `user_id`
                        FROM `world_cup`
                        LEFT JOIN `national`
                        ON `national_id`=`national`.`id`
                        WHERE `season_id`=$seasonId";
                Yii::$app->db->createCommand($sql)->execute();

                $sql = "INSERT INTO `achievement_player` (`player_id`, `place`, `division_id`, `season_id`, `national_id`, `tournament_type_id`)
                        SELECT `player_id`, `place`, `division_id`, `world_cup`.`season_id`, `world_cup`.`national_id`, " . TournamentType::NATIONAL . "
                        FROM `national_player_day`
                        LEFT JOIN `world_cup`
                        ON `national_player_day`.`national_id`=`world_cup`.`national_id`
                        WHERE `world_cup`.`season_id`=$seasonId";
                Yii::$app->db->createCommand($sql)->execute();
            } elseif (TournamentType::CHAMPIONSHIP === $schedule->tournament_type_id && Stage::TOUR_30 === $schedule->stage_id) {
                $sql = "INSERT INTO `achievement` (`federation_id`, `division_id`, `place`, `season_id`, `team_id`, `tournament_type_id`, `user_id`)
                        SELECT `federation_id`, `division_id`, `place`, `season_id`, `team_id`, " . TournamentType::CHAMPIONSHIP . ", `user_id`
                        FROM `championship`
                        LEFT JOIN `team`
                        ON `team_id`=`team`.`id`
                        WHERE `season_id`=$seasonId";
                Yii::$app->db->createCommand($sql)->execute();

                $sql = "INSERT INTO `achievement_player` (`federation_id`, `division_id`, `player_id`, `place`, `season_id`, `team_id`, `tournament_type_id`)
                        SELECT `federation_id`, `division_id`, `player`.`id`, `place`, `season_id`, `team`.`id`, " . TournamentType::CHAMPIONSHIP . "
                        FROM `championship`
                        LEFT JOIN `team`
                        ON `team_id`=`team`.`id`
                        LEFT JOIN `player`
                        ON `team`.`id`=`player`.`team_id`
                        WHERE `season_id`=$seasonId";
                Yii::$app->db->createCommand($sql)->execute();

                $sql = "INSERT INTO `achievement` (`place`, `season_id`, `team_id`, `tournament_type_id`, `user_id`)
                        SELECT `place`, `season_id`, `team_id`, " . TournamentType::CONFERENCE . ", `user_id`
                        FROM `conference`
                        LEFT JOIN `team`
                        ON `team_id`=`team`.`id`
                        WHERE `season_id`=$seasonId";
                Yii::$app->db->createCommand($sql)->execute();

                $sql = "INSERT INTO `achievement_player` (`player_id`, `place`, `season_id`, `team_id`, `tournament_type_id`)
                        SELECT `player`.`id`, `place`, `season_id`, `team`.`id`, " . TournamentType::CONFERENCE . "
                        FROM `conference`
                        LEFT JOIN `team`
                        ON `team_id`=`team`.`id`
                        LEFT JOIN `player`
                        ON `team`.`id`=`player`.`team_id`
                        WHERE `season_id`=$seasonId";
                Yii::$app->db->createCommand($sql)->execute();

            } elseif (TournamentType::LEAGUE === $schedule->tournament_type_id && in_array($schedule->stage_id, [
                    Stage::QUALIFY_1,
                    Stage::QUALIFY_2,
                    Stage::QUALIFY_3,
                    Stage::ROUND_OF_16,
                    Stage::QUARTER,
                    Stage::SEMI
                ], true)) {
                $nextStage = Schedule::find()
                    ->where(['tournament_type_id' => TournamentType::LEAGUE])
                    ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")>CURDATE()')
                    ->andWhere(['!=', 'stage_id', $schedule->stage_id])
                    ->orderBy(['id' => SORT_ASC])
                    ->limit(1)
                    ->one();
                if (!$nextStage) {
                    continue;
                }

                $sql = "INSERT INTO `achievement` (`season_id`, `stage_id`, `team_id`, `tournament_type_id`, `user_id`)
                        SELECT `season_id`, `stage_out_id`, `team_id`, " . TournamentType::LEAGUE . ", `user_id`
                        FROM `participant_league`
                        LEFT JOIN `team`
                        ON `team_id`=`team`.`id`
                        WHERE `season_id`=$seasonId
                        AND `stage_out_id`=" . $schedule->stage_id;
                Yii::$app->db->createCommand($sql)->execute();

                $sql = "INSERT INTO `achievement_player` (`player_id`, `season_id`, `stage_id`, `team_id`, `tournament_type_id`)
                        SELECT `player`.`id`, `season_id`, `stage_out_id`, `team`.`id`, " . TournamentType::LEAGUE . "
                        FROM `participant_league`
                        LEFT JOIN `team`
                        ON `team_id`=`team`.`id`
                        LEFT JOIN `player`
                        ON `team`.`id`=`player`.`team_id`
                        WHERE `season_id`=$seasonId
                        AND `stage_out_id`=" . $schedule->stage_id;
                Yii::$app->db->createCommand($sql)->execute();
            } elseif (TournamentType::LEAGUE === $schedule->tournament_type_id && Stage::TOUR_LEAGUE_6 === $schedule->stage_id) {
                $sql = "INSERT INTO `achievement` (`season_id`, `place`, `team_id`, `tournament_type_id`, `user_id`)
                        SELECT `season_id`, `stage_out_id`, `team_id`, " . TournamentType::LEAGUE . ", `user_id`
                        FROM `participant_league`
                        LEFT JOIN `team`
                        ON `team_id`=`team`.`id`
                        WHERE `season_id`=$seasonId
                        AND `stage_out_id` IN (3, 4)";
                Yii::$app->db->createCommand($sql)->execute();

                $sql = "INSERT INTO `achievement_player` (`player_id`, `season_id`, `stage_id`, `team_id`, `tournament_type_id`)
                        SELECT `player`.`id`, `season_id`, `stage_out_id`, `team`.`id`, " . TournamentType::LEAGUE . "
                        FROM `participant_league`
                        LEFT JOIN `team`
                        ON `team_id`=`team`.`id`
                        LEFT JOIN `player`
                        ON `team`.`id`=`player`.`team_id`
                        WHERE `season_id`=$seasonId
                        AND `stage_out_id` IN (3, 4)";
                Yii::$app->db->createCommand($sql)->execute();
            } elseif (TournamentType::LEAGUE === $schedule->tournament_type_id && Stage::FINAL_GAME === $schedule->stage_id) {
                $nextStage = Schedule::find()
                    ->where(['tournament_type_id' => TournamentType::LEAGUE])
                    ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")>CURDATE()')
                    ->orderBy(['id' => SORT_ASC])
                    ->limit(1)
                    ->one();
                if ($nextStage) {
                    continue;
                }

                $sql = "INSERT INTO `achievement` (`season_id`, `stage_id`, `team_id`, `tournament_type_id`, `user_id`)
                        SELECT $seasonId, `stage_out_id`, `team_id`, " . TournamentType::LEAGUE . ", `user_id`
                        FROM `participant_league`
                        LEFT JOIN `team`
                        ON `team_id`=`team`.`id`
                        WHERE `season_id`=$seasonId
                        AND `stage_out_id` IN (" . Stage::FINAL_GAME . ", 0)";
                Yii::$app->db->createCommand($sql)->execute();

                $sql = "INSERT INTO `achievement_player` (`player_id`, `season_id`, `stage_id`, `team_id`, `tournament_type_id`)
                        SELECT `player`.`id`, $seasonId, `stage_out_id`, `team`.`id`, " . TournamentType::LEAGUE . "
                        FROM `participant_league`
                        LEFT JOIN `team`
                        ON `team_id`=`team`.`id`
                        LEFT JOIN `player`
                        ON `team`.`id`=`player`.`team_id`
                        WHERE `season_id`=$seasonId
                        AND `stage_out_id` IN (" . Stage::FINAL_GAME . ", 0)";
                Yii::$app->db->createCommand($sql)->execute();
            }
        }
    }
}