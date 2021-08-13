<?php

// TODO refactor

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
            ->andWhere(['>', 'date_vip', time()])
            ->count();
    }

    /**
     * @return User[]
     */
    public static function getBirthdayBoys(): array
    {
        return User::find()
            ->andWhere(['birth_day' => date('d'), 'birth_month' => date('m')])
            ->orderBy(['id' => SORT_ASC])
            ->all();
    }
}
