<?php

// TODO refactor

namespace console\models\generator;

use Yii;
use yii\db\Exception;

/**
 * Class TeamAge
 * @package console\models\generator
 */
class TeamAge
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $sql = "UPDATE `team`
                LEFT JOIN
                (
                    SELECT AVG(`age`) AS `age`, `team_id`
                    FROM `player`
                    WHERE `team_id` IS NOT NULL
                    GROUP BY `team_id`
                ) AS `t1`
                ON `team_id`=`team`.`id`
                SET `player_average_age`=`age`
                WHERE `id`!=0";
        Yii::$app->db->createCommand($sql)->execute();
    }
}
