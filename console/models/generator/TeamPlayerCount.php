<?php

// TODO refactor

namespace console\models\generator;

use Yii;
use yii\db\Exception;

/**
 * Class TeamPlayerCount
 * @package console\models\generator
 */
class TeamPlayerCount
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $sql = "UPDATE `team`
                LEFT JOIN
                (
                    SELECT COUNT(`id`) AS `count_player`, `team_id`
                    FROM `player`
                    WHERE `team_id` IS NOT NULL
                    GROUP BY `team_id`
                ) AS `t1`
                ON `team_id`=`team`.`id`
                SET `player_number`=`count_player`
                WHERE `id`!=0";
        Yii::$app->db->createCommand($sql)->execute();
    }
}