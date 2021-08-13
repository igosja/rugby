<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\DayType;
use common\models\db\Player;
use common\models\db\Schedule;
use Yii;
use yii\db\Exception;
use yii\db\Expression;

/**
 * Class PlayerGameRow
 * @package console\models\generator
 */
class PlayerGameRow
{
    /**
     * @var int[] $scheduleIdsArray
     */
    private $scheduleIdsArray;

    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $this->scheduleIdsArray = Schedule::find()
            ->select(['id'])
            ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ->column();

        $this->updatePlayer();

        /**
         * @var Schedule $schedule
         */
        $schedule = Schedule::find()
            ->with(['tournamentType'])
            ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ->orderBy(['id' => SORT_ASC])
            ->limit(1)
            ->one();
        if (DayType::B === $schedule->tournamentType->day_type_id) {
            $this->b();
        } elseif (DayType::C === $schedule->tournamentType->day_type_id) {
            $this->c();
        }
    }

    /**
     * @return void
     */
    private function updatePlayer(): void
    {
        Player::updateAll(
            ['game_row_old' => new Expression('game_row')],
            ['and', 'game_row_old!=game_row', ['<=', 'age', Player::AGE_READY_FOR_PENSION]]
        );
    }

    /**
     * @return void
     * @throws Exception
     */
    private function b(): void
    {
        $sql = "UPDATE `player`
                SET `game_row`=IF(`game_row`<0, `game_row`-1, -1)
                WHERE `id` NOT IN
                (
                    SELECT `player_id` 
                    FROM `lineup`
                    LEFT JOIN `game`
                    ON `game_id`=`game`.`id`
                    LEFT JOIN `schedule`
                    ON `schedule_id`=`schedule`.`id`
                    LEFT JOIN `tournament_type`
                    ON `tournament_type_id`=`tournament_type`.`id`
                    WHERE `played` is null
                    AND `schedule_id` IN (" . implode(',', $this->scheduleIdsArray) . ")
                    AND `day_type_id`=" . DayType::B . "
                )";
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE `player`
                LEFT JOIN `lineup`
                ON `player`.`id`=`player_id`
                LEFT JOIN `game`
                ON `game_id`=`game`.`id`
                LEFT JOIN `schedule`
                ON `schedule_id`=`schedule`.`id`
                LEFT JOIN `tournament_type`
                ON `tournament_type_id`=`tournament_type`.`id`
                SET `game_row`=IF(`game_row`>0, `game_row`+1, 1)
                WHERE `played` IS NULL
                AND `schedule_id` IN (" . implode(',', $this->scheduleIdsArray) . ")
                AND `day_type_id`=" . DayType::B;
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * @return void
     * @throws Exception
     */
    private function c(): void
    {
        $sql = "UPDATE `player`
                LEFT JOIN `lineup`
                ON `player`.`id`=`player_id`
                LEFT JOIN `game`
                ON `game_id`=`game`.`id`
                SET `game_row`=IF(`game_row`>0, `game_row`+1, 1)
                WHERE `played` IS NULL
                AND `schedule_id` IN (" . implode(',', $this->scheduleIdsArray) . ")";
        Yii::$app->db->createCommand($sql)->execute();
    }
}