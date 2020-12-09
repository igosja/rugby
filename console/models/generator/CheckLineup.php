<?php

// TODO refactor

namespace console\models\generator;

use Yii;
use yii\db\Exception;

/**
 * Class CheckLineup
 * @package console\models\generator
 */
class CheckLineup
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $gameSql = "SELECT `game`.`id`
                    FROM `game`
                    LEFT JOIN `schedule`
                    ON `schedule_id`=`schedule`.`id`
                    WHERE `played` IS NULL
                    AND FROM_UNIXTIME(`date`, '%Y-%m-%d')=CURDATE()";

        $sql = "UPDATE `lineup`
                LEFT JOIN `player`
                ON `player_id`=`player`.`id`
                SET `player_id`=null
                WHERE `game_id` IN (" . $gameSql . ")
                AND ((`lineup`.`team_id`!=`player`.`team_id` AND `loan_team_id` IS NULL)
                OR (`lineup`.`team_id`!=`loan_team_id` AND `loan_team_id` IS NOT NULL))
                AND `lineup`.`team_id` IS NOT NULL";
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE `lineup`
                LEFT JOIN `player`
                ON `player_id`=`player`.`id`
                SET `player_id`=null
                WHERE `game_id` IN (" . $gameSql . ")
                AND `lineup`.`national_id`!=`player`.`national_id`
                AND `lineup`.`national_id` IS NOT NULL";
        Yii::$app->db->createCommand($sql)->execute();
    }
}