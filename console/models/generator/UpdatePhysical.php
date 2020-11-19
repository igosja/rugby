<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Physical;
use common\models\db\Player;
use common\models\db\Transfer;
use Yii;
use yii\db\Exception;

/**
 * Class UpdatePhysical
 * @package console\models\generator
 */
class UpdatePhysical
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $sql = "UPDATE `player`
                LEFT JOIN `physical_change`
                ON `player`.`id`=`player_id`
                LEFT JOIN `physical`
                ON `physical_id`=`physical`.`id`
                LEFT JOIN `schedule`
                ON `schedule_id`=`schedule`.`id`
                SET `player`.`physical_id`=`opposite_physical_id`
                WHERE FROM_UNIXTIME(`date`, '%Y-%m-%d')=CURDATE()";
        Yii::$app->db->createCommand($sql)->execute();

        Player::updateAllCounters(['physical_id' => 1], ['<=', 'age', Player::AGE_READY_FOR_PENSION]);
        Player::updateAll(['physical_id' => 1], ['>', 'physical_id', 20]);
        Player::updateAll(
            ['physical_id' => Physical::DEFAULT_ID],
            [
                'and',
                ['team_id' => null],
                [
                    'not',
                    [
                        'id' => Transfer::find()
                            ->select(['player_id'])
                            ->where(['voted' => 0])
                    ]
                ]
            ]);
    }
}