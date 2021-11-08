<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Player;
use common\models\db\Season;
use Yii;
use yii\db\Exception;

/**
 * Class PlayerPriceAndSalary
 * @package console\models\generator
 */
class PlayerPriceAndSalary
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $seasonId = Season::getCurrentSeason() + 1;

        $sql = "UPDATE `player`
                LEFT JOIN
                (
                    SELECT `player_id`, SUM(`level`) AS `level`
                    FROM `player_special`
                    LEFT JOIN `player`
                    ON `player_id`=`player`.`id`
                    WHERE `age`<=" . Player::AGE_READY_FOR_PENSION . "
                    GROUP BY `player_id`
                ) AS `t1`
                ON `t1`.`player_id`=`player`.`id`
                LEFT JOIN
                (
                    SELECT `player_id`, COUNT(`position_id`) AS `position`
                    FROM `player_position`
                    LEFT JOIN `player`
                    ON `player_id`=`player`.`id`
                    WHERE `age`<" . Player::AGE_READY_FOR_PENSION . "
                    GROUP BY `player_id`
                ) AS `t2`
                ON `t2`.`player_id`=`player`.`id`
                SET `price`=POW(150-(28-`age`), 2)*(`position`-2+`power_nominal`+IFNULL(`level`, 0)*10)
                WHERE `age`<" . Player::AGE_READY_FOR_PENSION;
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE `player`
                LEFT JOIN `team`
                ON `team_id`=`team`.`id`
                LEFT JOIN `base`
                ON `base_id`=`base`.`id`
                LEFT JOIN
                (
                    SELECT `team_id`,
                           `division_id`
                    FROM `championship`
                    WHERE `season_id`=$seasonId
                ) AS `t1`
                ON `team`.`id`=`t1`.`team_id`
                SET `player`.`salary`=`price`*(`level`+3)/10000*IF(`division_id`=1, 1, IF(`division_id`=2, 0.95, IF(`division_id`=3, 0.90, 0.8)))
                WHERE `t1`.`team_id` IS NOT NULL";
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE `player`
                LEFT JOIN `team`
                ON `team_id`=`team`.`id`
                LEFT JOIN `base`
                ON `base_id`=`base`.`id`
                LEFT JOIN
                (
                    SELECT `team_id`
                    FROM `conference`
                    WHERE `season_id`=$seasonId
                ) AS `t1`
                ON `team`.`id`=`t1`.`team_id`
                SET `player`.`salary`=`price`*(`level`+3)/10000*0.7
                WHERE `t1`.`team_id` IS NOT NULL";
        Yii::$app->db->createCommand($sql)->execute();
    }
}
