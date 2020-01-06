<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class TransferSpecial
 * @package common\models\db
 *
 * @property int $transfer_special_id
 * @property int $transfer_special_level
 * @property int $transfer_special_transfer_id
 * @property int $transfer_special_special_id
 */
class TransferSpecial extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%transfer_special}}';
    }
}
