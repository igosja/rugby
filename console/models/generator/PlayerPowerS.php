<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Player;
use Yii;
use yii\db\Exception;

/**
 * Class PlayerPowerS
 * @package console\models\generator
 */
class PlayerPowerS
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $sql = "UPDATE `player`
                LEFT JOIN
                (
                    SELECT `player_id`, SUM(`level`) AS `level`
                    FROM `player_special`
                    LEFT JOIN `player`
                    ON `player_id`=`player`.`id`
                    WHERE `age`<" . Player::AGE_READY_FOR_PENSION . "
                    GROUP BY `player_id`
                ) AS `t1`
                ON `player_id`=`player`.`id`
                SET `power_nominal_s`=`power_nominal`+IF(`level` IS NULL, 0, `level`)*`power_nominal`*5/100
                WHERE `age`<" . Player::AGE_READY_FOR_PENSION;
        Yii::$app->db->createCommand($sql)->execute();
    }
}