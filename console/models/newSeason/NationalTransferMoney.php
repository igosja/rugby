<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\National;
use common\models\db\NationalPlayerDay;
use common\models\db\NationalUserDay;
use common\models\db\Team;
use common\models\db\User;
use Exception;

/**
 * Class NationalTransferMoney
 * @package console\models\newSeason
 */
class NationalTransferMoney
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $nationalArray = National::find()
            ->where(['!=', 'id', 0])
            ->andWhere(['>', 'finance', 0])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($nationalArray as $national) {
            /**
             * @var National $national
             */
            $moneyCoach = round($national->finance / 5);
            $moneyPlayer = $national->finance - $moneyCoach;

            $totalUserDay = NationalUserDay::find()
                ->where(['national_id' => $national->id])
                ->sum('day');

            $nationalUserDayArray = NationalUserDay::find()
                ->where(['national_id' => $national->id])
                ->orderBy(['id' => SORT_ASC])
                ->all();
            foreach ($nationalUserDayArray as $nationalUserDay) {
                $money = round($moneyCoach / $totalUserDay * $nationalUserDay->day);

                $user = User::find()
                    ->where(['id' => $nationalUserDay->user_id])
                    ->limit(1)
                    ->one();

                Finance::log([
                    'finance_text_id' => FinanceText::INCOME_COACH,
                    'user_id' => $user->id,
                    'value' => $money,
                    'value_after' => $user->finance + $money,
                    'value_before' => $user->finance,
                ]);

                $user->finance += $money;
                $user->save(true, ['finance']);
            }

            $totalPlayerDay = NationalPlayerDay::find()
                ->where(['national_id' => $national->id])
                ->sum('day');

            $nationalPlayerDayArray = NationalPlayerDay::find()
                ->select(['day' => 'SUM(day)', 'team_id'])
                ->where(['national_id' => $national->id])
                ->groupBy('team_id')
                ->orderBy(['team_id' => SORT_ASC])
                ->all();
            foreach ($nationalPlayerDayArray as $nationalPlayerDay) {
                $money = round($moneyPlayer / $totalPlayerDay * $nationalPlayerDay->day);

                $team = Team::find()
                    ->where(['id' => $nationalPlayerDay->team_id])
                    ->limit(1)
                    ->one();
                Finance::log([
                    'finance_text_id' => FinanceText::INCOME_NATIONAL,
                    'team_id' => $team->id,
                    'value' => $money,
                    'value_after' => $team->finance + $money,
                    'value_before' => $team->finance,
                ]);

                $team->finance += $money;
                $team->save(true, ['finance']);
            }

            Finance::log([
                'finance_text_id' => FinanceText::OUTCOME_NATIONAL,
                'national_id' => $national->id,
                'value' => -$national->finance,
                'value_after' => 0,
                'value_before' => $national->finance,
            ]);

            $national->finance = 0;
            $national->save(true, ['finance']);
        }
    }
}
