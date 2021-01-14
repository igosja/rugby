<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Position;
use common\models\db\Season;
use common\models\db\Special;
use Yii;
use yii\db\Exception;

/**
 * Class Snapshot
 * @package console\models\generator
 */
class Snapshot
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $seasonId = Season::getCurrentSeason();

        $sql = "INSERT INTO `snapshot`
                SET `base`=(SELECT AVG(`level`) FROM `team` LEFT JOIN `base` ON `base_id` = `base`.`id` WHERE `team`.`id`!=0),
                    `base_total`=(SELECT AVG(`base`.`level` + `base_medical`.`level` + `base_physical`.`level` + `base_school`.`level` + `base_scout`.`level` + `base_training`.`level`) FROM `team` LEFT JOIN `base` ON `base_id` = `base`.`id` LEFT JOIN `base_medical` ON `base_medical_id` = `base_medical`.`id` LEFT JOIN `base_physical` ON `base_physical_id` = `base_physical`.`id` LEFT JOIN `base_school` ON `base_school_id` = `base_school`.`id` LEFT JOIN `base_scout` ON `base_scout_id` = `base_scout`.`id` LEFT JOIN `base_training` ON `base_training_id` = `base_training`.`id` WHERE `team`.`id`!=0),
                    `base_medical`=(SELECT AVG(`base_medical`.`level`) FROM `team` LEFT JOIN `base_medical` ON `base_medical_id` = `base_medical`.`id` WHERE `team`.`id`!=0),
                    `base_physical`=(SELECT AVG(`base_physical`.`level`) FROM `team` LEFT JOIN `base_physical` ON `base_physical_id` = `base_physical`.`id` WHERE `team`.`id`!=0),
                    `base_school`=(SELECT AVG(`base_school`.`level`) FROM `team` LEFT JOIN `base_school` ON `base_school_id` = `base_school`.`id` WHERE `team`.`id`!=0),
                    `base_scout`=(SELECT AVG(`base_scout`.`level`) FROM `team` LEFT JOIN `base_scout` ON `base_scout_id` = `base_scout`.`id` WHERE `team`.`id`!=0),
                    `base_training`=(SELECT AVG(`base_training`.`level`) FROM `team` LEFT JOIN `base_training` ON `base_training_id` = `base_training`.`id` WHERE `team`.`id`!=0),
                    `federation`=(SELECT COUNT(`id`) FROM (SELECT `id` FROM `city` WHERE `country_id` != 0 GROUP BY `country_id`) AS `t`),
                    `date`=UNIX_TIMESTAMP(),
                    `manager`=(SELECT COUNT(`id`) FROM `user` WHERE `date_login`>UNIX_TIMESTAMP()-604800),
                    `manager_vip`=(SELECT COUNT(`id`) FROM `user` WHERE `date_login`>UNIX_TIMESTAMP()-604800 AND `date_vip`>UNIX_TIMESTAMP())/(SELECT COUNT(`id`) FROM `user` WHERE `date_login`>UNIX_TIMESTAMP()-604800)*100,
                    `manager_with_team`=(SELECT COUNT(`count`) FROM (SELECT COUNT(`id`) AS `count` FROM `team` WHERE `user_id`!=0 GROUP BY `user_id`) AS `t`),
                    `player`=(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0),
                    `player_age`=(SELECT AVG(`age`) FROM `player` WHERE `team_id`!=0),
                    `player_in_team`=(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)/(SELECT COUNT(`id`) FROM `team` WHERE `id`!=0),
                    `player_position_centre`=(SELECT COUNT(`player`.`id`) FROM `player` LEFT JOIN `player_position` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `position_id`=" . Position::CENTRE . ")/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_position_eight`=(SELECT COUNT(`player`.`id`) FROM `player` LEFT JOIN `player_position` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `position_id`=" . Position::EIGHT . ")/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_position_flanker`=(SELECT COUNT(`player`.`id`) FROM `player` LEFT JOIN `player_position` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `position_id`=" . Position::FLANKER . ")/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_position_fly_half`=(SELECT COUNT(`player`.`id`) FROM `player` LEFT JOIN `player_position` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `position_id`=" . Position::FLY_HALF . ")/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_position_full_back`=(SELECT COUNT(`player`.`id`) FROM `player` LEFT JOIN `player_position` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `position_id`=" . Position::FULL_BACK . ")/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_position_hooker`=(SELECT COUNT(`player`.`id`) FROM `player` LEFT JOIN `player_position` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `position_id`=" . Position::HOOKER . ")/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_position_lock`=(SELECT COUNT(`player`.`id`) FROM `player` LEFT JOIN `player_position` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `position_id`=" . Position::LOCK . ")/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_position_prop`=(SELECT COUNT(`player`.`id`) FROM `player` LEFT JOIN `player_position` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `position_id`=" . Position::PROP . ")/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_position_scrum_half`=(SELECT COUNT(`player`.`id`) FROM `player` LEFT JOIN `player_position` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `position_id`=" . Position::SCRUM_HALF . ")/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_position_wing`=(SELECT COUNT(`player`.`id`) FROM `player` LEFT JOIN `player_position` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `position_id`=" . Position::WING . ")/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_power`=(SELECT AVG(`power_nominal`) FROM `player` WHERE `team_id`!=0),
                    `player_special_no`=(SELECT COUNT(`id`) FROM (SELECT `player`.`id` FROM `player` LEFT JOIN `player_special` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `player_id` IS NULL GROUP BY `player`.`id`) AS `t`)/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_special_one`=(SELECT COUNT(`id`) FROM (SELECT `player`.`id` FROM `player` LEFT JOIN `player_special` ON `player`.`id`=`player_id` WHERE `team_id`!=0 GROUP BY `player`.`id` HAVING COUNT(`player_id`)=1) AS `t`)/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_special_two`=(SELECT COUNT(`id`) FROM (SELECT `player`.`id` FROM `player` LEFT JOIN `player_special` ON `player`.`id`=`player_id` WHERE `team_id`!=0 GROUP BY `player`.`id` HAVING COUNT(`player_id`)=2) AS `t`)/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_special_three`=(SELECT COUNT(`id`) FROM (SELECT `player`.`id` FROM `player` LEFT JOIN `player_special` ON `player`.`id`=`player_id` WHERE `team_id`!=0 GROUP BY `player`.`id` HAVING COUNT(`player_id`)=3) AS `t`)/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_special_four`=(SELECT COUNT(`id`) FROM (SELECT `player`.`id` FROM `player` LEFT JOIN `player_special` ON `player`.`id`=`player_id` WHERE `team_id`!=0 GROUP BY `player`.`id` HAVING COUNT(`player_id`)=4) AS `t`)/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_special_athletic`=(SELECT COUNT(`id`) FROM (SELECT `player`.`id` FROM `player` LEFT JOIN `player_special` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `special_id`=" . Special::ATHLETIC . " GROUP BY `player`.`id`) AS `t`)/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_special_combine`=(SELECT COUNT(`id`) FROM (SELECT `player`.`id` FROM `player` LEFT JOIN `player_special` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `special_id`=" . Special::COMBINE . " GROUP BY `player`.`id`) AS `t`)/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_special_idol`=(SELECT COUNT(`id`) FROM (SELECT `player`.`id` FROM `player` LEFT JOIN `player_special` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `special_id`=" . Special::IDOL . " GROUP BY `player`.`id`) AS `t`)/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_special_leader`=(SELECT COUNT(`id`) FROM (SELECT `player`.`id` FROM `player` LEFT JOIN `player_special` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `special_id`=" . Special::LEADER . " GROUP BY `player`.`id`) AS `t`)/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_special_moul`=(SELECT COUNT(`id`) FROM (SELECT `player`.`id` FROM `player` LEFT JOIN `player_special` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `special_id`=" . Special::MOUL . " GROUP BY `player`.`id`) AS `t`)/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_special_pass`=(SELECT COUNT(`id`) FROM (SELECT `player`.`id` FROM `player` LEFT JOIN `player_special` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `special_id`=" . Special::PASS . " GROUP BY `player`.`id`) AS `t`)/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_special_power`=(SELECT COUNT(`id`) FROM (SELECT `player`.`id` FROM `player` LEFT JOIN `player_special` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `special_id`=" . Special::POWER . " GROUP BY `player`.`id`) AS `t`)/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_special_ruck`=(SELECT COUNT(`id`) FROM (SELECT `player`.`id` FROM `player` LEFT JOIN `player_special` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `special_id`=" . Special::RUCK . " GROUP BY `player`.`id`) AS `t`)/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_special_scrum`=(SELECT COUNT(`id`) FROM (SELECT `player`.`id` FROM `player` LEFT JOIN `player_special` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `special_id`=" . Special::SCRUM . " GROUP BY `player`.`id`) AS `t`)/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_special_speed`=(SELECT COUNT(`id`) FROM (SELECT `player`.`id` FROM `player` LEFT JOIN `player_special` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `special_id`=" . Special::SPEED . " GROUP BY `player`.`id`) AS `t`)/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_special_tackle`=(SELECT COUNT(`id`) FROM (SELECT `player`.`id` FROM `player` LEFT JOIN `player_special` ON `player`.`id`=`player_id` WHERE `team_id`!=0 AND `special_id`=" . Special::TACKLE . " GROUP BY `player`.`id`) AS `t`)/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `player_with_position`=(SELECT COUNT(`id`) FROM (SELECT `player`.`id` FROM `player` LEFT JOIN `player_position` ON `player`.`id`=`player_id` GROUP BY `player`.`id` HAVING COUNT(`player_id`)=2) AS `t`)/(SELECT COUNT(`id`) FROM `player` WHERE `team_id`!=0)*100,
                    `season_id`=$seasonId,
                    `team`=(SELECT COUNT(`id`) FROM `team` WHERE `id`!=0),
                    `team_finance`=(SELECT AVG(`finance`) FROM `team` WHERE `id`!=0),
                    `team_to_manager`=(SELECT COUNT(`id`) FROM `team` WHERE `user_id`!=0)/(SELECT COUNT(`id`) FROM (SELECT `id` FROM `team` WHERE `user_id`!=0 GROUP BY `user_id`) AS `t`),
                    `stadium`=(SELECT AVG(`capacity`) FROM `stadium` WHERE `id`!=0)";
        Yii::$app->db->createCommand($sql)->execute();
    }
}
