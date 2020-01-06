<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class FinanceText
 * @package common\models\db
 *
 * @property int $finance_text_id
 * @property string $finance_text_text
 */
class FinanceText extends AbstractActiveRecord
{
    const COUNTRY_TRANSFER = 29;
    const INCOME_BUILDING_BASE = 17;
    const INCOME_BUILDING_STADIUM = 15;
    const INCOME_COACH = 35;
    const INCOME_DEAL_CHECK = 34;
    const INCOME_LOAN = 21;
    const INCOME_NATIONAL = 26;
    const INCOME_PENSION = 25;
    const INCOME_PRIZE_CHAMPIONSHIP = 4;
    const INCOME_PRIZE_CONFERENCE = 5;
    const INCOME_PRIZE_LEAGUE = 3;
    const INCOME_PRIZE_OFF_SEASON = 6;
    const INCOME_PRIZE_VIP = 1;
    const INCOME_PRIZE_WORLD_CUP = 2;
    const INCOME_REFERRAL = 28;
    const INCOME_SCOUT_STYLE = 33;
    const INCOME_TICKET = 7;
    const INCOME_TRAINING_POSITION = 30;
    const INCOME_TRAINING_POWER = 32;
    const INCOME_TRAINING_SPECIAL = 31;
    const INCOME_TRANSFER = 18;
    const INCOME_TRANSFER_FIRST_TEAM = 20;
    const OUTCOME_BUILDING_BASE = 16;
    const OUTCOME_BUILDING_STADIUM = 14;
    const OUTCOME_GAME = 8;
    const OUTCOME_LOAN = 22;
    const OUTCOME_MAINTENANCE = 23;
    const OUTCOME_NATIONAL = 37;
    const OUTCOME_SALARY = 9;
    const OUTCOME_SCOUT_STYLE = 13;
    const OUTCOME_TRAINING_POSITION = 10;
    const OUTCOME_TRAINING_POWER = 12;
    const OUTCOME_TRAINING_SPECIAL = 11;
    const OUTCOME_TRANSFER = 19;
    const TEAM_RE_REGISTER = 24;
    const USER_TRANSFER = 27;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%finance_text}}';
    }
}
