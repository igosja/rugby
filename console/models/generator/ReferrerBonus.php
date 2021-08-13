<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\User;
use Exception;
use yii\db\Expression;

/**
 * Class ReferrerBonus
 * @package console\models\generator
 */
class ReferrerBonus
{
    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $userArray = User::find()
            ->joinWith(['teams'])
            ->where(['not', ['referrer_user_id' => null]])
            ->andWhere(['is_referrer_done' => false])
            ->andWhere(['>', 'date_login', new Expression('date_register+2592000')])
            ->andWhere(['not', ['user_id' => null]])
            ->groupBy(['user.id'])
            ->orderBy(['user.id' => SORT_ASC])
            ->each();
        foreach ($userArray as $user) {
            /**
             * @var User $user
             */
            $sum = 1000000;

            Finance::log([
                'finance_text_id' => FinanceText::INCOME_REFERRAL,
                'user_id' => $user->referrer_user_id,
                'value' => $sum,
                'value_after' => $user->referrerUser->finance + $sum,
                'value_before' => $user->referrerUser->finance,
            ]);

            $user->referrerUser->finance += $sum;
            $user->referrerUser->save(true, ['finance']);

            $user->is_referrer_done = true;
            $user->save(true, ['is_referrer_done']);
        }
    }
}
