<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class TransferApplication
 * @package common\models\db
 *
 * @property int $transfer_application_id
 * @property int $transfer_application_date
 * @property int $transfer_application_deal_reason_id
 * @property int $transfer_application_only_one
 * @property int $transfer_application_price
 * @property int $transfer_application_team_id
 * @property int $transfer_application_transfer_id
 * @property int $transfer_application_user_id
 */
class TransferApplication extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%transfer_application}}';
    }
}
