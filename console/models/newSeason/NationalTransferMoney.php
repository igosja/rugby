<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\Finance;
use common\models\FinanceText;
use common\models\National;
use common\models\NationalPlayerDay;
use common\models\NationalUserDay;
use common\models\Team;
use common\models\User;
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
    public function execute()
    {
        $nationalArray = National::find()
            ->where(['!=', 'national_id', 0])
            ->andWhere(['>', 'national_finance', 0])
            ->orderBy(['national_id' => SORT_ASC])
            ->each();
        foreach ($nationalArray as $national) {
            /**
             * @var National $national
             */
            $moneyCoach = round($national->national_finance / 5);
            $moneyPlayer = $national->national_finance - $moneyCoach;

            $totalUserDay = NationalUserDay::find()
                ->where(['national_user_day_national_id' => $national->national_id])
                ->sum('national_user_day_day');

            $nationalUserDayArray = NationalUserDay::find()
                ->where(['national_user_day_national_id' => $national->national_id])
                ->orderBy(['national_user_day_id' => SORT_ASC])
                ->all();
            foreach ($nationalUserDayArray as $nationalUserDay) {
                $money = round($moneyCoach / $totalUserDay * $nationalUserDay->national_user_day_day);

                $user = User::find()
                    ->where(['user_id' => $nationalUserDay->national_user_day_user_id])
                    ->limit(1)
                    ->one();

                Finance::log([
                    'finance_finance_text_id' => FinanceText::INCOME_COACH,
                    'finance_user_id' => $user->user_id,
                    'finance_value' => $money,
                    'finance_value_after' => $user->user_finance + $money,
                    'finance_value_before' => $user->user_finance,
                ]);

                $user->user_finance = $user->user_finance + $money;
                $user->save(true, ['user_finance']);
            }

            $totalPlayerDay = NationalPlayerDay::find()
                ->where(['national_player_day_national_id' => $national->national_id])
                ->sum('national_player_day_day');

            $nationalPlayerDayArray = NationalPlayerDay::find()
                ->select(['national_player_day_day' => 'SUM(national_player_day_day)', 'national_player_day_team_id'])
                ->where(['national_player_day_national_id' => $national->national_id])
                ->groupBy('national_player_day_team_id')
                ->orderBy(['national_player_day_team_id' => SORT_ASC])
                ->all();
            foreach ($nationalPlayerDayArray as $nationalPlayerDay) {
                $money = round($moneyPlayer / $totalPlayerDay * $nationalPlayerDay->national_player_day_day);

                $team = Team::find()
                    ->where(['team_id' => $nationalPlayerDay->national_player_day_team_id])
                    ->limit(1)
                    ->one();
                Finance::log([
                    'finance_finance_text_id' => FinanceText::INCOME_NATIONAL,
                    'finance_team_id' => $team->team_id,
                    'finance_value' => $money,
                    'finance_value_after' => $team->team_finance + $money,
                    'finance_value_before' => $team->team_finance,
                ]);

                $team->team_finance = $team->team_finance + $money;
                $team->save(true, ['team_finance']);
            }

            Finance::log([
                'finance_finance_text_id' => FinanceText::OUTCOME_NATIONAL,
                'finance_national_id' => $national->national_id,
                'finance_value' => -$national->national_finance,
                'finance_value_after' => 0,
                'finance_value_before' => $national->national_finance,
            ]);

            $national->national_finance = 0;
            $national->save(true, ['national_finance']);
        }
    }
}
