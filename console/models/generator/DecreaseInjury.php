<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Player;
use Yii;
use yii\db\Exception;

/**
 * Class DecreaseInjury
 * @package console\models\generator
 */
class DecreaseInjury
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        Player::updateAllCounters(['injury_day' => -1], ['is_injury' => true]);

        $sql = "UPDATE `player`
                LEFT JOIN `team`
                ON `team_id`=`team`.`id`
                LEFT JOIN `base_medical`
                ON `base_medical_id`=`base_medical`.`id`
                SET `player`.`tire`=`base_medical`.`tire`,
                    `is_injury`=FALSE
                WHERE `is_injury`=TRUE
                AND `injury_day`<=0";
        Yii::$app->db->createCommand($sql)->execute();
    }
}