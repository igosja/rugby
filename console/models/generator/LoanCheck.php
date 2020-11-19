<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\Loan;
use common\models\db\LoanVote;
use common\models\db\Team;
use Exception;

/**
 * Class LoanCheck
 * @package console\models\generator
 */
class LoanCheck
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $loanArray = Loan::find()
            ->where(['voted' => null])
            ->andWhere(['not', ['ready' => null]])
            ->andWhere('FROM_UNIXTIME(`ready`+604800, "%Y-%m-%d")<=CURDATE()')
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($loanArray as $loan) {
            /**
             * @var Loan $loan
             */
            $check = LoanVote::find()
                ->where(['loan_id' => $loan->id])
                ->sum('rating');
            if ($check < 0) {
                $sellerTeam = Team::find()
                    ->where(['id' => $loan->team_seller_id])
                    ->limit(1)
                    ->one();
                Finance::log([
                    'finance_text_id' => FinanceText::INCOME_LOAN,
                    'player_id' => $loan->player_id,
                    'team_id' => $loan->team_seller_id,
                    'value' => -$loan->price_buyer,
                    'value_after' => $sellerTeam->finance - $loan->price_buyer,
                    'value_before' => $sellerTeam->finance,
                ]);

                $sellerTeam->finance -= $loan->price_buyer;
                $sellerTeam->save();

                $buyerTeam = Team::find()
                    ->where(['id' => $loan->team_buyer_id])
                    ->limit(1)
                    ->one();

                Finance::log([
                    'finance_text_id' => FinanceText::OUTCOME_LOAN,
                    'player_id' => $loan->player_id,
                    'team_id' => $loan->team_buyer_id,
                    'value' => $loan->price_buyer,
                    'value_after' => $buyerTeam->finance + $loan->price_buyer,
                    'value_before' => $buyerTeam->finance,
                ]);

                $buyerTeam->finance += $loan->price_buyer;
                $buyerTeam->save();

                $loan->cancel = time();
                $loan->save(true, ['cancel']);
            }
        }

        Loan::updateAll(
            ['voted' => time()],
            [
                'and',
                ['not', ['ready' => null]],
                ['voted' => null],
                'FROM_UNIXTIME(`ready`+604800, "%Y-%m-%d")<=CURDATE()',
            ]
        );
    }
}
