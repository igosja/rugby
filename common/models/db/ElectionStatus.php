<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ElectionStatus
 * @package common\models\db
 *
 * @property int $election_status_id
 * @property string $election_status_name
 */
class ElectionStatus extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_status}}';
    }
}
