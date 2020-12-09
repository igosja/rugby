<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Scout;
use Yii;
use yii\db\Exception;

/**
 * Class UpdateScout
 * @package console\models\generator
 */
class UpdateScout
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $sql = "UPDATE `scout`
                LEFT JOIN `team`
                ON `team_id`=`team`.`id`
                LEFT JOIN `base_scout`
                ON `base_scout_id`=`base_scout`.`id`
                SET `percent`=`percent`+`scout_speed_min`+(`scout_speed_max`-`scout_speed_min`)/2*RAND()
                WHERE `ready` IS NULL";
        Yii::$app->db->createCommand($sql)->execute();

        Scout::updateAll(
            ['percent' => 100, 'ready' => time()],
            ['and', ['ready' => null], ['>=', 'percent', 100]]
        );
    }
}
