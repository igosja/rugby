<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\History;
use common\models\db\HistoryText;
use common\models\db\Team;
use common\models\db\Transfer;
use common\models\db\TransferVote;
use Exception;

/**
 * Class TransferCheck
 * @package console\models\generator
 */
class TransferCheck
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $transferArray = Transfer::find()
            ->with(['player'])
            ->where(['voted' => null])
            ->andWhere(['not', ['ready' => null]])
            ->andWhere('FROM_UNIXTIME(`ready`+604800, "%Y-%m-%d")<=CURDATE()')
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($transferArray as $transfer) {
            /**
             * @var Transfer $transfer
             */
            $check = TransferVote::find()
                ->where(['transfer_id' => $transfer->id])
                ->sum('rating');
            if ($check < 0) {
                $schoolPrice = round($transfer->price_buyer / 100);

                $schoolTeam = Team::find()
                    ->where(['id' => $transfer->player->school_team_id])
                    ->limit(1)
                    ->one();

                Finance::log([
                    'finance_text_id' => FinanceText::INCOME_TRANSFER_FIRST_TEAM,
                    'player_id' => $transfer->player_id,
                    'team_id' => $transfer->player->school_team_id,
                    'value' => -$schoolPrice,
                    'value_after' => $schoolTeam->finance - $schoolPrice,
                    'value_before' => $schoolTeam->finance,
                ]);

                $schoolTeam->finance -= $schoolPrice;
                $schoolTeam->save(true, ['finance']);

                if ($transfer->team_seller_id) {
                    $sellerTeam = Team::find()
                        ->where(['id' => $transfer->team_seller_id])
                        ->limit(1)
                        ->one();

                    Finance::log([
                        'finance_text_id' => FinanceText::INCOME_TRANSFER,
                        'player_id' => $transfer->player_id,
                        'team_id' => $transfer->team_seller_id,
                        'value' => -$transfer->price_buyer,
                        'value_after' => $sellerTeam->finance - $transfer->price_buyer,
                        'value_before' => $sellerTeam->finance,
                    ]);

                    $sellerTeam->finance -= $transfer->price_buyer;
                    $sellerTeam->save(true, ['finance']);
                }

                if ($transfer->team_buyer_id) {
                    $buyerTeam = Team::find()
                        ->where(['id' => $transfer->team_buyer_id])
                        ->limit(1)
                        ->one();

                    Finance::log([
                        'finance_text_id' => FinanceText::OUTCOME_TRANSFER,
                        'player_id' => $transfer->player_id,
                        'team_id' => $transfer->team_buyer_id,
                        'value' => $transfer->price_buyer,
                        'value_after' => $buyerTeam->finance + $transfer->price_buyer,
                        'value_before' => $buyerTeam->finance,
                    ]);

                    $buyerTeam->finance += $transfer->price_buyer;
                    $buyerTeam->save(true, ['finance']);
                }

                $transfer->player->squad_id = null;
                $transfer->player->date_no_action = null;
                $transfer->player->is_no_deal = null;
                $transfer->player->team_id = $transfer->team_seller_id;
                $transfer->player->save();

                History::log([
                    'history_text_id' => HistoryText::PLAYER_TRANSFER,
                    'player_id' => $transfer->player_id,
                    'team_id' => $transfer->team_buyer_id,
                    'team_2_id' => $transfer->team_seller_id,
                    'value' => $transfer->price_buyer,
                ]);

                $transfer->cancel = time();
                $transfer->save(true, ['cancel']);
            }
        }

        Transfer::updateAll(
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
