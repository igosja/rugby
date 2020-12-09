<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Team;
use Yii;
use yii\db\Exception;

/**
 * Class UserDecrementAutoForVocation
 * @package console\models\generator
 */
class UserDecrementAutoForVocation
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $sql = "UPDATE `team`
                LEFT JOIN `user`
                ON `user_id`=`user`.`id`
                SET `auto_number`=4
                WHERE `auto_number`>=" . Team::MAX_AUTO_GAMES . "
                AND `user_id`!=0
                AND `user`.`id` IN (
                    SELECT `user_id`
                    FROM `user_holiday`
                    WHERE `date_end` IS NULL
                )";
        Yii::$app->db->createCommand($sql)->execute();

        Team::updateAll(['auto_number' => 0], ['and', ['!=', 'auto_number', 0], ['user_id' => 0]]);
    }
}
