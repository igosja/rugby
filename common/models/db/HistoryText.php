<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class HistoryText
 * @package common\models\db
 *
 * @property int $id
 * @property string $text
 */
class HistoryText extends AbstractActiveRecord
{
    public const BUILDING_DOWN = 16;
    public const BUILDING_UP = 15;
    public const CHANGE_SPECIAL = 20;
    public const CHANGE_STYLE = 19;
    public const PLAYER_BONUS_POINT = 33;
    public const PLAYER_BONUS_POSITION = 34;
    public const PLAYER_BONUS_SPECIAL = 35;
    public const PLAYER_CHAMPIONSHIP_SPECIAL = 32;
    public const PLAYER_EXCHANGE = 38;
    public const PLAYER_FREE = 41;
    public const PLAYER_FROM_SCHOOL = 24;
    public const PLAYER_GAME_POINT_MINUS = 31;
    public const PLAYER_GAME_POINT_PLUS = 30;
    public const PLAYER_INJURY = 36;
    public const PLAYER_LOAN = 39;
    public const PLAYER_LOAN_BACK = 40;
    public const PLAYER_PENSION_GO = 26;
    public const PLAYER_PENSION_SAY = 25;
    public const PLAYER_TRAINING_POINT = 27;
    public const PLAYER_TRAINING_POSITION = 28;
    public const PLAYER_TRAINING_SPECIAL = 29;
    public const PLAYER_TRANSFER = 37;
    public const STADIUM_DOWN = 18;
    public const STADIUM_UP = 17;
    public const TEAM_REGISTER = 1;
    public const TEAM_RE_REGISTER = 2;
    public const USER_MANAGER_NATIONAL_IN = 7;
    public const USER_MANAGER_NATIONAL_OUT = 8;
    public const USER_MANAGER_TEAM_IN = 3;
    public const USER_MANAGER_TEAM_OUT = 4;
    public const USER_PRESIDENT_IN = 11;
    public const USER_PRESIDENT_OUT = 12;
    public const USER_VICE_NATIONAL_IN = 9;
    public const USER_VICE_NATIONAL_OUT = 10;
    public const USER_VICE_PRESIDENT_IN = 13;
    public const USER_VICE_PRESIDENT_OUT = 14;
    public const USER_VICE_TEAM_IN = 5;
    public const USER_VICE_TEAM_OUT = 6;
    public const VIP_1_PLACE = 21;
    public const VIP_2_PLACE = 22;
    public const VIP_3_PLACE = 23;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%history_text}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['text'], 'required'],
            [['text'], 'trim'],
            [['text'], 'string', 'max' => 255],
            [['text'], 'unique'],
        ];
    }
}
