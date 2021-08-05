<?php

// TODO refactor

namespace console\models\generator;

use Yii;
use yii\db\Exception;

/**
 * Class PresidentVip
 * @package console\models\generator
 */
class PresidentVip
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $sql = "UPDATE `user`
               SET `date_vip`=UNIX_TIMESTAMP()+2592000
               WHERE `id` IN
               (
                   SELECT `id`
                   FROM (
                       SELECT `user`.`id`
                       FROM `federation`
                       LEFT JOIN `user`
                       ON `president_user_id`=`user`.`id`
                       WHERE `date_vip`<UNIX_TIMESTAMP()+604800
                       AND `user`.`id`!=0
                   ) AS `t1`
               )
               OR `id` IN
               (
                   SELECT `id`
                   FROM (
                       SELECT `user`.`id`
                       FROM `federation`
                       LEFT JOIN `user`
                       ON `vice_user_id`=`user`.`id`
                       WHERE `date_vip`<UNIX_TIMESTAMP()+604800
                       AND `user`.`id`!=0
                   ) AS `t2`
               )";
        Yii::$app->db->createCommand($sql)->execute();
    }
}
