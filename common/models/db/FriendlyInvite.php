<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class FriendlyInvite
 * @package common\models\db
 *
 * @property int $friendly_invite_id
 * @property int $friendly_invite_date
 * @property int $friendly_invite_friendly_invite_status_id
 * @property int $friendly_invite_guest_team_id
 * @property int $friendly_invite_guest_user_id
 * @property int $friendly_invite_home_team_id
 * @property int $friendly_invite_home_user_id
 * @property int $friendly_invite_schedule_id
 */
class FriendlyInvite extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%friendly_invite}}';
    }
}
