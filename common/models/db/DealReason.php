<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class DealReason
 * @package common\models\db
 *
 * @property int $deal_reason_id
 * @property string $deal_reason_text
 */
class DealReason extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%deal_reason}}';
    }
}
