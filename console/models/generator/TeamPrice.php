<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Stadium;
use common\models\db\Team;
use Yii;
use yii\db\Exception;
use yii\db\Expression;

/**
 * Class TeamPrice
 * @package console\models\generator
 */
class TeamPrice
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $sql = "UPDATE `team`
                LEFT JOIN `stadium`
                ON `stadium_id`=`stadium`.`id`
                LEFT JOIN
                (
                    SELECT SUM(`price`) AS `player_price`,
                           SUM(`salary`) AS `player_salary`,
                           `team_id`
                    FROM `player`
                    GROUP BY `team_id`
                ) AS `t1`
                ON `team_id`=`team`.`id`
                SET `price_base`=(`base_id`-1)*500000+(`base_medical_id`+`base_physical_id`+`base_school_id`+`base_scout_id`+`base_training_id`-5)*250000,
                    `price_player`=`player_price`,
                    `salary`=`player_salary`,
                    `price_stadium`=POW(`capacity`, 1.1)*" . Stadium::ONE_SIT_PRICE_BUY . "
                WHERE `team`.`id`!=0";
        Yii::$app->db->createCommand($sql)->execute();

        Team::updateAll(
            ['price_total' => new Expression('price_base+price_player+price_stadium')],
            ['!=', 'id', 0]
        );
    }
}
