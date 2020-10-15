<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class FinanceText
 * @package common\models\db
 *
 * @property int $id
 * @property string $text
 */
class FinanceText extends AbstractActiveRecord
{
    public const COUNTRY_TRANSFER = 29;
    public const INCOME_BUILDING_BASE = 17;
    public const INCOME_BUILDING_STADIUM = 15;
    public const INCOME_COACH = 35;
    public const INCOME_DEAL_CHECK = 34;
    public const INCOME_LOAN = 21;
    public const INCOME_NATIONAL = 26;
    public const INCOME_PENSION = 25;
    public const INCOME_PRIZE_CHAMPIONSHIP = 4;
    public const INCOME_PRIZE_CONFERENCE = 5;
    public const INCOME_PRIZE_LEAGUE = 3;
    public const INCOME_PRIZE_OFF_SEASON = 6;
    public const INCOME_PRIZE_VIP = 1;
    public const INCOME_PRIZE_WORLD_CUP = 2;
    public const INCOME_REFERRAL = 28;
    public const INCOME_SCOUT_STYLE = 33;
    public const INCOME_TICKET = 7;
    public const INCOME_TRAINING_POSITION = 30;
    public const INCOME_TRAINING_POWER = 32;
    public const INCOME_TRAINING_SPECIAL = 31;
    public const INCOME_TRANSFER = 18;
    public const INCOME_TRANSFER_FIRST_TEAM = 20;
    public const OUTCOME_BUILDING_BASE = 16;
    public const OUTCOME_BUILDING_STADIUM = 14;
    public const OUTCOME_GAME = 8;
    public const OUTCOME_LOAN = 22;
    public const OUTCOME_MAINTENANCE = 23;
    public const OUTCOME_NATIONAL = 37;
    public const OUTCOME_SALARY = 9;
    public const OUTCOME_SCOUT_STYLE = 13;
    public const OUTCOME_TRAINING_POSITION = 10;
    public const OUTCOME_TRAINING_POWER = 12;
    public const OUTCOME_TRAINING_SPECIAL = 11;
    public const OUTCOME_TRANSFER = 19;
    public const TEAM_RE_REGISTER = 24;
    public const USER_TRANSFER = 27;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%finance_text}}';
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
        ];
    }
}
