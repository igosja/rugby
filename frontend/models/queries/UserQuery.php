<?php

namespace frontend\models\queries;

use common\models\db\User;

/**
 * Class UserQuery
 * @package frontend\models\queries
 */
class UserQuery
{
    /**
     * @return int|string
     */
    public static function countVipUsers()
    {
        return User::find()
            ->where(['>', 'user_date_vip', time()])
            ->count();
    }
}
