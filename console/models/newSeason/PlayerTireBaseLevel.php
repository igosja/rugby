<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\Building;
use Yii;
use yii\db\Exception;

/**
 * Class PlayerTireBaseLevel
 * @package console\models\newSeason
 */
class PlayerTireBaseLevel
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $sql = "UPDATE `player`
                LEFT JOIN `team`
                ON `team_id`=`team`.`id`
                LEFT JOIN `base_medical`
                ON `base_medical_id`=`base_medical`.`id`
                SET `player`.`tire`=`base_medical`.`tire`
                WHERE `team_id`!=0
                AND `loan_team_id`=0";
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE `player`
                LEFT JOIN `team`
                ON `loan_team_id`=`team`.`id`
                LEFT JOIN `base_medical`
                ON `base_medical_id`=`base_medical`.`id`
                SET `player`.`tire`=`base_medical`.`tire`
                WHERE `team_id`!=0
                AND `loan_team_id`!=0";
        Yii::$app->db->createCommand($sql)->execute();
    }
}
