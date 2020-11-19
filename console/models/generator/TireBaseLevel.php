<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Building;
use common\models\db\Schedule;
use common\models\db\Stage;
use common\models\db\TournamentType;
use Yii;
use yii\db\Exception;

/**
 * Class TireBaseLevel
 * @package console\models\generator
 */
class TireBaseLevel
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $check = Schedule::find()
            ->where('FROM_UNIXTIME(`date`-86400, "%Y-%m-%d")=CURDATE()')
            ->andWhere([
                'stage_id' => Stage::TOUR_1,
                'tournament_type_id' => TournamentType::CHAMPIONSHIP
            ])
            ->limit(1)
            ->one();
        if (!$check) {
            return;
        }

        $sql = "UPDATE `player`
                LEFT JOIN `team`
                ON `team_id`=`team`.`id`
                LEFT JOIN `base_medical`
                ON `base_medical_id`=`base_medical`.`id`
                SET `player`.`tire`=`base_medical`.`tire`
                WHERE `player`.`team_id`!=0
                AND `loan_team_id` IS NULL
                AND `team_id` NOT IN (
                    SELECT `team_id`
                    FROM `building_base`
                    WHERE `ready` IS NULL
                    AND `building_id` IN (" . Building::BASE . ", " . Building::MEDICAL . ")
                )";
        Yii::$app->db->createCommand($sql)->execute();


        $sql = "UPDATE `player`
                LEFT JOIN `team`
                ON `team_id`=`team`.`id`
                LEFT JOIN `base_medical`
                ON `base_medical_id`=`base_medical`.`id`
                SET `player`.`tire`=50
                WHERE `player`.`team_id`!=0
                AND `loan_team_id` IS NULL
                AND `team_id` IN (
                    SELECT `team_id`
                    FROM `building_base`
                    WHERE `ready` IS NULL
                    AND `building_id` IN (" . Building::BASE . ", " . Building::MEDICAL . ")
                )";
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE `player`
                LEFT JOIN `team`
                ON `loan_team_id`=`team`.`id`
                LEFT JOIN `base_medical`
                ON `base_medical_id`=`base_medical`.`id`
                SET `player`.`tire`=`base_medical`.`tire`
                WHERE `player`.`team_id`!=0
                AND `loan_team_id` IS NOT NULL
                AND `team_id` NOT IN (
                    SELECT `team_id`
                    FROM `building_base`
                    WHERE `ready` IS NULL
                    AND `building_id` IN (" . Building::BASE . ", " . Building::MEDICAL . ")
                )";
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE `player`
                LEFT JOIN `team`
                ON `loan_team_id`=`team`.`id`
                LEFT JOIN `base_medical`
                ON `base_medical_id`=`base_medical`.`id`
                SET `player`.`tire`=50
                WHERE `player`.`team_id`!=0
                AND `loan_team_id` IS NOT NULL
                AND `team_id` IN (
                    SELECT `team_id`
                    FROM `building_base`
                    WHERE `ready` IS NULL
                    AND `building_id` IN (" . Building::BASE . ", " . Building::MEDICAL . ")
                )";
        Yii::$app->db->createCommand($sql)->execute();
    }
}
