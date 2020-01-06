<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Transfer
 * @package common\models\db
 *
 * @property int $transfer_id
 * @property int $transfer_age
 * @property int $transfer_cancel
 * @property int $transfer_checked
 * @property int $transfer_date
 * @property int $transfer_player_id
 * @property int $transfer_player_price
 * @property int $transfer_power
 * @property int $transfer_price_buyer
 * @property int $transfer_price_seller
 * @property int $transfer_ready
 * @property int $transfer_season_id
 * @property int $transfer_team_buyer_id
 * @property int $transfer_team_seller_id
 * @property int $transfer_to_league
 * @property int $transfer_user_buyer_id
 * @property int $transfer_user_seller_id
 */
class Transfer extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%transfer}}';
    }
}
