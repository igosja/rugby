<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\User;
use common\models\db\UserHoliday;

/**
 * Class UserHolidayEnd
 * @package console\models\generator
 */
class UserHolidayEnd
{
    /**
     * @return void
     */
    public function execute(): void
    {
        UserHoliday::updateAll(
            ['date_end' => time()],
            [
                'and',
                ['date_end' => null],
                ['not', ['user_id' => User::find()->andWhere(['<', 'user_date_vip', time()])]],
                ['<=', 'date_start', time() - User::MAX_HOLIDAY * 86400],
            ]
        );
        UserHoliday::updateAll(
            ['date_end' => time()],
            [
                'and',
                ['date_end' => null],
                ['not', ['user_id' => User::find()->andWhere(['>=', 'user_date_vip', time()])]],
                ['<=', 'date_start', time() - User::MAX_VIP_HOLIDAY * 86400],
            ]
        );
    }
}
