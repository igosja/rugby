<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\DayType;
use common\models\db\Mood;
use common\models\db\Player;
use common\models\db\Schedule;
use common\models\db\Special;
use Yii;
use yii\db\Exception;

/**
 * Class PlayerTire
 * @package console\models\generator
 */
class PlayerTire
{
    /**
     * @var int[] $scheduleIdsArray
     */
    private $scheduleIdsArray;

    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $this->scheduleIdsArray = Schedule::find()
            ->select(['id'])
            ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ->column();

        $this->updateMood();

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

        $this->updatePlayer();
    }

    /**
     * @return void
     * @throws Exception
     */
    private function updateMood(): void
    {
        $sql = "UPDATE `player`
                LEFT JOIN `lineup`
                ON `player`.`id`=`player_id`
                LEFT JOIN `game`
                ON `game_id`=`game`.`id`
                SET `mood_id`=IF(`lineup`.`team_id`=`home_team_id`, `home_mood_id`, `guest_mood_id`)
                WHERE `schedule_id` IN (" . implode(',', $this->scheduleIdsArray) . ")";
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * @return void
     * @throws Exception
     */
    private function b()
    {
        $sql = "UPDATE `player`
                LEFT JOIN
                (
                    SELECT `level`,
                           `player_id`
                    FROM `player_special`
                    WHERE `special_id`=" . Special::ATHLETIC . "
                ) AS `t1`
                ON `t1`.`player_id`=`player`.`id`
                LEFT JOIN `lineup`
                ON `player`.`id`=`lineup`.`player_id`
                LEFT JOIN `game`
                ON `game_id`=`game`.`id`
                LEFT JOIN `schedule`
                ON `schedule_id`=`schedule`.`id`
                LEFT JOIN `tournament_type`
                ON `tournament_type_id`=`tournament_type`.`id`
                SET `tire`=`tire`+IF((CEIL((`player`.`age`-12)/11)+`game_row`)*(" . Mood::REST . "-`mood_id`)-IF(`level` IS NULL, 0, `level`)>0, (CEIL((`player`.`age`-12)/11)+`game_row`)*(" . Mood::REST . "-`mood_id`)-IF(`level` IS NULL, 0, `level`), 0)
                WHERE `schedule_id` IN (" . implode(',', $this->scheduleIdsArray) . ")
                AND `game_row`>0
                AND `player`.`age`<=" . Player::AGE_READY_FOR_PENSION . "
                AND `mood_id`>0
                AND `player`.`team_id` IS NOT NULL
                AND `day_type_id`=" . DayType::B . "";
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE `player`
                LEFT JOIN `team`
                ON `team_id`=`team`.`id`
                LEFT JOIN `base_physical`
                ON `base_physical_id`=`base_physical`.`id`
                SET `tire`=`tire`-IF(`game_row_old`<=-2, 4, IF(`game_row_old`=-1, 5, IF(`game_row_old`=1, 15, IF(`game_row_old`=2, 12, IF(`game_row_old`=3, 10, IF(`game_row_old`=4, 8, IF(`game_row_old`=5, 6, 5)))))))+`tire_bonus`
                WHERE `game_row`<0
                AND `player`.`id`
                NOT IN
                (
                    SELECT `player_id`
                    FROM `lineup`
                    LEFT JOIN `game`
                    ON `game_id`=`game`.`id`
                    LEFT JOIN `schedule`
                    ON `schedule_id`=`schedule`.`id`
                    LEFT JOIN `tournament_type`
                    ON `tournament_type_id`=`tournament_type`.`id`
                    WHERE `schedule_id` IN (" . implode(',', $this->scheduleIdsArray) . ")
                    AND `day_type_id`=" . DayType::B . "
                )
                AND `player`.`age`<=" . Player::AGE_READY_FOR_PENSION . "
                AND `player`.`team_id` IS NOT NULL";
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * @return void
     * @throws Exception
     */
    private function c(): void
    {
        $sql = "UPDATE `player`
                LEFT JOIN
                (
                    SELECT `level`,
                           `player_id`
                    FROM `player_special`
                    WHERE `special_id`=" . Special::ATHLETIC . "
                ) AS `t1`
                ON `t1`.`player_id`=`player`.`id`
                LEFT JOIN `lineup`
                ON `player`.`id`=`player_id`
                LEFT JOIN `game`
                ON `game_id`=`game`.`id`
                SET `tire`=`tire`+IF((FLOOR((`player`.`age`-12)/11)+CEIL(`game_row`/2))*(" . Mood::REST . "-`mood_id`)-IF(`level` IS NULL, 0, `level`)>0, (FLOOR((`player`.`age`-12)/11)+CEIL(`game_row`/2))*(" . Mood::REST . "-`mood_id`)-IF(`level` IS NULL, 0, `level`)>0, 0)
                WHERE `schedule_id` IN (" . implode(',', $this->scheduleIdsArray) . ")
                AND `game_row`>0
                AND `player`.`age`<40
                AND `mood_id`>0
                AND `player`.`team_id` IS NOT NULL";
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * @return void
     */
    private function updatePlayer(): void
    {
        Player::updateAll(['mood_id' => null], ['<=', 'age', Player::AGE_READY_FOR_PENSION]);
        Player::updateAll(
            ['tire' => Player::TIRE_MAX_FOR_LINEUP],
            ['and', ['<=', 'age', Player::AGE_READY_FOR_PENSION], ['>', 'tire', Player::TIRE_MAX_FOR_LINEUP]]
        );
        Player::updateAll(
            ['tire' => 0],
            ['and', ['<=', 'age', Player::AGE_READY_FOR_PENSION], ['<', 'tire', 0]]
        );
        Player::updateAll(['tire' => 0], ['team_id' => null]);
        Player::updateAll(
            ['tire' => Player::TIRE_DEFAULT],
            [
                'and',
                ['not', ['team_id' => null]],
                ['is_injury' => true],
                ['<', 'tire', Player::TIRE_DEFAULT]
            ]
        );
    }
}