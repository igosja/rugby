<?php

// TODO refactor

namespace console\models\generator;

use Yii;
use yii\db\Exception;

/**
 * Class MakePlayed
 * @package console\models\generator
 */
class MakePlayed
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $sql = "UPDATE `game`
                LEFT JOIN `schedule`
                ON `schedule_id`=`schedule`.`id`
                SET `played`=UNIX_TIMESTAMP()
                WHERE `played` IS NULL
                AND FROM_UNIXTIME(`date`, '%Y-%m-%d')=CURDATE()";
        Yii::$app->db->createCommand($sql)->execute();
    }
}
