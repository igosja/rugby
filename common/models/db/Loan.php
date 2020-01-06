<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Loan
 * @package common\models\db
 *
 * @property int $loan_id
 * @property int $loan_age
 * @property int $loan_cancel
 * @property int $loan_checked
 * @property int $loan_date
 * @property int $loan_day
 * @property int $loan_day_max
 * @property int $loan_day_min
 * @property int $loan_player_id
 * @property int $loan_player_price
 * @property int $loan_power
 * @property int $loan_price_buyer
 * @property int $loan_price_seller
 * @property int $loan_ready
 * @property int $loan_season_id
 * @property int $loan_team_buyer_id
 * @property int $loan_team_seller_id
 * @property int $loan_user_buyer_id
 * @property int $loan_user_seller_id
 */
class Loan extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%loan}}';
    }
}
