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
                ON `player_team_id`=`team_id`
                LEFT JOIN `base_medical`
                ON `team_base_medical_id`=`base_medical_id`
                SET `player_tire`=`base_medical_tire`
                WHERE `player_team_id`!=0
                AND `player_loan_team_id`=0
                AND `team_id` NOT IN (
                    SELECT `building_base_team_id`
                    FROM `building_base`
                    WHERE `building_base_ready`=0
                    AND `building_base_building_id` IN (" . Building::BASE . ", " . Building::MEDICAL . ")
                )";
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE `player`
                LEFT JOIN `team`
                ON `player_team_id`=`team_id`
                SET `player_tire`=50
                WHERE `player_team_id`!=0
                AND `player_loan_team_id`=0
                AND `team_id` IN (
                    SELECT `building_base_team_id`
                    FROM `building_base`
                    WHERE `building_base_ready`=0
                    AND `building_base_building_id` IN (" . Building::BASE . ", " . Building::MEDICAL . ")
                )";
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE `player`
                LEFT JOIN `team`
                ON `player_loan_team_id`=`team_id`
                LEFT JOIN `base_medical`
                ON `team_base_medical_id`=`base_medical_id`
                SET `player_tire`=`base_medical_tire`
                WHERE `player_team_id`!=0
                AND `player_loan_team_id`!=0
                AND `player_loan_team_id` NOT IN (
                    SELECT `building_base_team_id`
                    FROM `building_base`
                    WHERE `building_base_ready`=0
                    AND `building_base_building_id` IN (" . Building::BASE . ", " . Building::MEDICAL . ")
                )";
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE `player`
                LEFT JOIN `team`
                ON `player_loan_team_id`=`team_id`
                SET `player_tire`=50
                WHERE `player_team_id`!=0
                AND `player_loan_team_id`!=0
                AND `player_loan_team_id` IN (
                    SELECT `building_base_team_id`
                    FROM `building_base`
                    WHERE `building_base_ready`=0
                    AND `building_base_building_id` IN (" . Building::BASE . ", " . Building::MEDICAL . ")
                )";
        Yii::$app->db->createCommand($sql)->execute();
    }
}
