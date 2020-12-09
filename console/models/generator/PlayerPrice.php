<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Player;
use Yii;
use yii\db\Exception;

/**
 * Class PlayerPrice
 * @package console\models\generator
 */
class PlayerPrice
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
    }
}
