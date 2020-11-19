<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Season;
use Yii;
use yii\db\Exception;

/**
 * Class UpdateUserTotalRating
 * @package console\models\generator
 */
class UpdateUserTotalRating
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $seasonId = Season::getCurrentSeason();

        $sql = "UPDATE `user_rating`
                LEFT JOIN
                (
                    SELECT SUM(`auto`) AS `auto_total`,
                           SUM(`collision_loose`) AS `collision_loose_total`,
                           SUM(`collision_win`) AS `collision_win_total`,
                           SUM(`draw`) AS `draw_total`,
                           SUM(`draw_equal`) AS `draw_equal_total`,
                           SUM(`draw_strong`) AS `draw_strong_total`,
                           SUM(`draw_weak`) AS `draw_weak_total`,
                           SUM(`game`) AS `game_total`,
                           SUM(`loose`) AS `loose_total`,
                           SUM(`loose_equal`) AS `loose_equal_total`,
                           SUM(`loose_strong`) AS `loose_strong_total`,
                           SUM(`loose_super`) AS `loose_super_total`,
                           SUM(`loose_weak`) AS `loose_weak_total`,
                           `user_id` AS `user_id_total`,
                           SUM(`vs_super`) AS `vs_super_total`,
                           SUM(`vs_rest`) AS `vs_rest_total`,
                           SUM(`win`) AS `win_total`,
                           SUM(`win_equal`) AS `win_equal_total`,
                           SUM(`win_strong`) AS `win_strong_total`,
                           SUM(`win_super`) AS `win_super_total`,
                           SUM(`win_weak`) AS `win_weak_total`
                    FROM `user_rating`
                    WHERE `season_id` IS NOT NULL
                    GROUP BY `user_id`
                ) AS `t1`
                ON `user_id`=`user_id_total`
                SET `auto`=`auto_total`,
                    `collision_loose`=`collision_loose_total`,
                    `collision_win`=`collision_win_total`,
                    `draw`=`draw_total`,
                    `draw_equal`=`draw_equal_total`,
                    `draw_strong`=`draw_strong_total`,
                    `draw_weak`=`draw_weak_total`,
                    `game`=`game_total`,
                    `loose`=`loose_total`,
                    `loose_equal`=`loose_equal_total`,
                    `loose_strong`=`loose_strong_total`,
                    `loose_super`=`loose_super_total`,
                    `loose_weak`=`loose_weak_total`,
                    `vs_super`=`vs_super_total`,
                    `vs_rest`=`vs_rest_total`,
                    `win`=`win_total`,
                    `win_equal`=`win_equal_total`,
                    `win_strong`=`win_strong_total`,
                    `win_super`=`win_super_total`,
                    `win_weak`=`win_weak_total`
                WHERE `season_id` IS NULL";
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE `user_rating`
                SET `rating`=500
                    -`auto`*0.5
                    -`collision_loose`*3
                    +`collision_win`*3.3
                    +`draw_strong`*1.1
                    -`draw_weak`*1
                    +`game`*0.01
                    -`loose_equal`*3
                    -`loose_strong`*1
                    -`loose_super`*5
                    -`loose_weak`*5
                    +`vs_super`*1.1
                    -`vs_rest`*1
                    +`win_equal`*3.3
                    +`win_strong`*5.5
                    +`win_super`*5.5
                    +`win_weak`*1.1
                WHERE `season_id`=$seasonId";
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE `user_rating`
                LEFT JOIN
                (
                    SELECT `rating`-500 AS `rating_100`,
                           `user_id` AS `user_id_100`
                    FROM `user_rating`
                    WHERE `season_id`=$seasonId
                    AND `season_id` IS NOT NULL
                ) AS `t1`
                ON `user_id`=`user_id_100`
                LEFT JOIN
                (
                    SELECT ROUND((`rating`-500)*0.75, 2) AS `rating_75`,
                           `user_id` AS `user_id_75`
                    FROM `user_rating`
                    WHERE `season_id`=$seasonId-1
                    AND `season_id` IS NOT NULL
                ) AS `t2`
                ON `user_id`=`user_id_75`
                LEFT JOIN
                (
                    SELECT ROUND((`rating`-500)*0.5, 2) AS `rating_50`,
                           `user_id` AS `user_id_50`
                    FROM `user_rating`
                    WHERE `season_id`=$seasonId-2
                    AND `season_id` IS NOT NULL
                ) AS `t3`
                ON `user_id`=`user_id_50`
                LEFT JOIN
                (
                    SELECT ROUND((`rating`-500)*0.25, 2) AS `rating_25`,
                           `user_id` AS `user_id_25`
                    FROM `user_rating`
                    WHERE `season_id`=$seasonId-3
                    AND `season_id` IS NOT NULL
                ) AS `t4`
                ON `user_id`=`user_id_25`
                SET `rating`=500+(IFNULL(`rating_100`, 0)+IFNULL(`rating_75`, 0)+IFNULL(`rating_50`, 0)+IFNULL(`rating_25`, 0))/(IF($seasonId>4, 4, $seasonId))
                WHERE `season_id` IS NULL";
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE `user`
                LEFT JOIN `user_rating`
                ON `user`.`id`=`user_id`
                SET `user`.`rating`=IFNULL(`user_rating`.`rating`, 500)
                WHERE `user`.`id`!=0
                AND `season_id` IS NULL";
        Yii::$app->db->createCommand($sql)->execute();
    }
}
