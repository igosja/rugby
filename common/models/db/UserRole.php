<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class UserRole
 * @package common\models\db
 *
 * @property int $user_role_id
 * @property string $user_role_name
 */
class UserRole extends AbstractActiveRecord
{
    const ADMIN = 5;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user_role}}';
    }
}
