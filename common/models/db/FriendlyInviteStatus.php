<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class FriendlyInviteStatus
 * @package common\models\db
 *
 * @property int $friendly_invite_status_id
 * @property string $friendly_invite_status_name
 */
class FriendlyInviteStatus extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%friendly_invite_status}}';
    }
}
