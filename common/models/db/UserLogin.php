<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class UserLogin
 * @package common\models\db
 *
 * @property int $user_login_id
 * @property string $user_login_agent
 * @property string $user_login_ip
 * @property int $user_login_user_id
 */
class UserLogin extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user_login}}';
    }
}
