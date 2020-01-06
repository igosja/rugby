<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class HistoryText
 * @package common\models\db
 *
 * @property int $history_text_id
 * @property string $history_text_text
 */
class HistoryText extends AbstractActiveRecord
{
    const BUILDING_DOWN = 16;
    const BUILDING_UP = 15;
    const CHANGE_SPECIAL = 20;
    const CHANGE_STYLE = 19;
    const PLAYER_BONUS_POINT = 33;
    const PLAYER_BONUS_POSITION = 34;
    const PLAYER_BONUS_SPECIAL = 35;
    const PLAYER_CHAMPIONSHIP_SPECIAL = 32;
    const PLAYER_EXCHANGE = 38;
    const PLAYER_FREE = 41;
    const PLAYER_FROM_SCHOOL = 24;
    const PLAYER_GAME_POINT_MINUS = 31;
    const PLAYER_GAME_POINT_PLUS = 30;
    const PLAYER_INJURY = 36;
    const PLAYER_LOAN = 39;
    const PLAYER_LOAN_BACK = 40;
    const PLAYER_PENSION_GO = 26;
    const PLAYER_PENSION_SAY = 25;
    const PLAYER_TRAINING_POINT = 27;
    const PLAYER_TRAINING_POSITION = 28;
    const PLAYER_TRAINING_SPECIAL = 29;
    const PLAYER_TRANSFER = 37;
    const STADIUM_DOWN = 18;
    const STADIUM_UP = 17;
    const TEAM_REGISTER = 1;
    const TEAM_RE_REGISTER = 2;
    const USER_MANAGER_NATIONAL_IN = 7;
    const USER_MANAGER_NATIONAL_OUT = 8;
    const USER_MANAGER_TEAM_IN = 3;
    const USER_MANAGER_TEAM_OUT = 4;
    const USER_PRESIDENT_IN = 11;
    const USER_PRESIDENT_OUT = 12;
    const USER_VICE_NATIONAL_IN = 9;
    const USER_VICE_NATIONAL_OUT = 10;
    const USER_VICE_PRESIDENT_IN = 13;
    const USER_VICE_PRESIDENT_OUT = 14;
    const USER_VICE_TEAM_IN = 5;
    const USER_VICE_TEAM_OUT = 6;
    const VIP_1_PLACE = 21;
    const VIP_2_PLACE = 22;
    const VIP_3_PLACE = 23;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%history_text}}';
    }
}
