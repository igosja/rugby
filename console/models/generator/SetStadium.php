<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\TournamentType;
use Yii;
use yii\db\Exception;

/**
 * Class SetStadium
 * @package console\models\generator
 */
class SetStadium
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
                LEFT JOIN `national`
                ON `home_national_id`=`national`.`id`
                SET `game`.`stadium_id`=`national`.`stadium_id`
                WHERE `played` IS NULL
                AND FROM_UNIXTIME(`date`, '%Y-%m-%d')=CURDATE()
                AND `tournament_type_id`=" . TournamentType::NATIONAL;
        Yii::$app->db->createCommand($sql)->execute();
    }
}