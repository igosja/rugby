<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\Season;
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
                    SELECT `player_special_player_id`, SUM(`player_special_level`) AS `special_level`
                    FROM `player_special`
                    LEFT JOIN `player`
                    ON `player_special_player_id`=`player_id`
                    WHERE `player_age`<40
                    GROUP BY `player_special_player_id`
                ) AS `t1`
                ON `player_special_player_id`=`player_id`
                LEFT JOIN
                (
                    SELECT `player_position_player_id`, COUNT(`player_position_position_id`) AS `position`
                    FROM `player_position`
                    LEFT JOIN `player`
                    ON `player_position_player_id`=`player_id`
                    WHERE `player_age`<40
                    GROUP BY `player_position_player_id`
                ) AS `t2`
                ON `player_position_player_id`=`player_id`
                SET `player_price`=POW(150-(28-`player_age`), 2)*(`position`-1+`player_power_nominal`+IFNULL(`special_level`, 0))
                WHERE `player_age`<40";
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE `player`
                LEFT JOIN `team`
                ON `player_team_id`=`team_id`
                LEFT JOIN `base`
                ON `team_base_id`=`base_id`
                LEFT JOIN
                (
                    SELECT `championship_team_id`,
                           `championship_division_id`
                    FROM `championship`
                    WHERE `championship_season_id`=$seasonId
                ) AS `t1`
                ON `team_id`=`championship_team_id`
                SET `player_salary`=`player_price`*(`base_level`+3)/10000*IF(`championship_division_id`=1, 1, IF(`championship_division_id`=2, 0.95, IF(`championship_division_id`=3, 0.90, 0.8)))
                WHERE `championship_team_id` IS NOT NULL";
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE `player`
                LEFT JOIN `team`
                ON `player_team_id`=`team_id`
                LEFT JOIN `base`
                ON `team_base_id`=`base_id`
                LEFT JOIN
                (
                    SELECT `conference_team_id`
                    FROM `conference`
                    WHERE `conference_season_id`=$seasonId
                ) AS `t1`
                ON `team_id`=`conference_team_id`
                SET `player_salary`=`player_price`*(`base_level`+3)/10000*0.7
                WHERE `conference_team_id` IS NOT NULL";
        Yii::$app->db->createCommand($sql)->execute();
    }
}