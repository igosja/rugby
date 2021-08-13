<?php

// TODO refactor

namespace console\models\generator;

use Yii;
use yii\db\Exception;

/**
 * Class CountryAuto
 * @package console\models\generator
 */
class CountryAuto
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $sql = "UPDATE `federation`
                LEFT JOIN
                (
                    SELECT COUNT(`game`.`id`) AS `count_home_game`,
                           `federation`.`id`
                    FROM `game`
                    LEFT JOIN `team`
                    ON `home_team_id`=`team`.`id`
                    LEFT JOIN `stadium`
                    ON `team`.`stadium_id`=`stadium`.`id`
                    LEFT JOIN `city`
                    ON `city_id`=`city`.`id`
                    LEFT JOIN `country`
                    ON `city`.`country_id`=`country`.`id`
                    LEFT JOIN `federation`
                    ON `country`.`id`=`federation`.`country_id`
                    LEFT JOIN `schedule`
                    ON `schedule_id`=`schedule`.`id`
                    WHERE FROM_UNIXTIME(`schedule`.`date`, '%Y-%m-%d')=CURDATE()
                    GROUP BY `federation`.`id`
                ) AS `t1`
                ON `federation`.`id`=`t1`.`id`
                LEFT JOIN
                (
                    SELECT COUNT(`game`.`id`) AS `count_guest_game`,
                           `federation`.`id`
                    FROM `game`
                    LEFT JOIN `team`
                    ON `guest_team_id`=`team`.`id`
                    LEFT JOIN `stadium`
                    ON `team`.`stadium_id`=`stadium`.`id`
                    LEFT JOIN `city`
                    ON `city_id`=`city`.`id`
                    LEFT JOIN `country`
                    ON `city`.`country_id`=`country`.`id`
                    LEFT JOIN `federation`
                    ON `country`.`id`=`federation`.`country_id`
                    LEFT JOIN `schedule`
                    ON `schedule_id`=`schedule`.`id`
                    WHERE FROM_UNIXTIME(`schedule`.`date`, '%Y-%m-%d')=CURDATE()
                    GROUP BY `federation`.`id`
                ) AS `t2`
                ON `federation`.`id`=`t2`.`id`
                LEFT JOIN
                (
                    SELECT COUNT(`game`.`id`) AS `count_home_game_auto`,
                           `federation`.`id`
                    FROM `game`
                    LEFT JOIN `team`
                    ON `home_team_id`=`team`.`id`
                    LEFT JOIN `stadium`
                    ON `team`.`stadium_id`=`stadium`.`id`
                    LEFT JOIN `city`
                    ON `city_id`=`city`.`id`
                    LEFT JOIN `country`
                    ON `city`.`country_id`=`country`.`id`
                    LEFT JOIN `federation`
                    ON `country`.`id`=`federation`.`country_id`
                    LEFT JOIN `schedule`
                    ON `schedule_id`=`schedule`.`id`
                    WHERE FROM_UNIXTIME(`schedule`.`date`, '%Y-%m-%d')=CURDATE()
                    AND `home_auto`=TRUE
                    GROUP BY `federation`.`id`
                ) AS `t3`
                ON `federation`.`id`=`t3`.`id`
                LEFT JOIN
                (
                    SELECT COUNT(`game`.`id`) AS `count_guest_game_auto`,
                           `federation`.`id`
                    FROM `game`
                    LEFT JOIN `team`
                    ON `guest_team_id`=`team`.`id`
                    LEFT JOIN `stadium`
                    ON `team`.`stadium_id`=`stadium`.`id`
                    LEFT JOIN `city`
                    ON `city_id`=`city`.`id`
                    LEFT JOIN `country`
                    ON `city`.`country_id`=`country`.`id`
                    LEFT JOIN `federation`
                    ON `country`.`id`=`federation`.`country_id`
                    LEFT JOIN `schedule`
                    ON `schedule_id`=`schedule`.`id`
                    WHERE FROM_UNIXTIME(`schedule`.`date`, '%Y-%m-%d')=CURDATE()
                    AND `guest_auto`=TRUE
                    GROUP BY `federation`.`id`
                ) AS `t4`
                ON `federation`.`id`=`t4`.`id`
                SET `game`=`game`+IFNULL(`count_home_game`, 0)+IFNULL(`count_guest_game`, 0),
                    `auto`=`auto`+IFNULL(`count_home_game_auto`, 0)+IFNULL(`count_guest_game_auto`, 0)
                WHERE `t1`.`id` IS NOT NULL
                AND `t1`.`id`!=0
                AND `t2`.`id` IS NOT NULL
                AND `t2`.`id`!=0";
        Yii::$app->db->createCommand($sql)->execute();
    }
}