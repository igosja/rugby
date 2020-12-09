<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Player;
use Yii;
use yii\db\Exception;

/**
 * Class PlayerRealPower
 * @package console\models\generator
 */
class PlayerRealPower
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $sql = "UPDATE `player`
                LEFT JOIN `physical`
                ON `physical_id`=`physical`.`id`
                SET `power_real`=`power_nominal`*(100-`tire`)/100*`value`/100
                WHERE `age`<" . Player::AGE_READY_FOR_PENSION;
        Yii::$app->db->createCommand($sql)->execute();
    }
}