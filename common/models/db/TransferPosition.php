<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class TransferPosition
 * @package common\models\db
 *
 * @property int $transfer_position_id
 * @property int $transfer_position_position_id
 * @property int $transfer_position_transfer_id
 */
class TransferPosition extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%transfer_position}}';
    }
}
