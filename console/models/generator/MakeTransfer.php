<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\DealReason;
use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\History;
use common\models\db\HistoryText;
use common\models\db\Loan;
use common\models\db\PhysicalChange;
use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\Team;
use common\models\db\Transfer;
use common\models\db\TransferApplication;
use common\models\db\TransferPosition;
use common\models\db\TransferSpecial;
use Throwable;
use yii\db\StaleObjectException;

/**
 * Class MakeTransfer
 * @package console\models\generator
 */
class MakeTransfer
{
    /**
     * @return void
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function execute(): void
    {
        $seasonId = Season::getCurrentSeason();

        $scheduleQuery = Schedule::find()
            ->select(['id'])
            ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ->limit(1);

        $transferArray = Transfer::find()
            ->joinWith(['player'])
            ->where(['ready' => null])
            ->andWhere('`date`<=UNIX_TIMESTAMP()-86400')
            ->orderBy(['player.price' => SORT_DESC, 'transfer.id' => SORT_ASC])
            ->each();
        foreach ($transferArray as $transfer) {
            /**
             * @var Transfer $transfer
             */
            $teamArray = [$transfer->team_seller_id];

            /**
             * @var Transfer[] $historyArray
             */
            $historyArray = Transfer::find()
                ->where(['season_id' => $seasonId])
                ->andWhere(['not', ['team_buyer_id' => null]])
                ->andWhere(['not', ['team_seller_id' => null]])
                ->andWhere([
                    'or',
                    ['team_seller_id' => $transfer->team_seller_id],
                    ['team_buyer_id' => $transfer->team_seller_id],
                ])
                ->andWhere(['ready' => null])
                ->all();
            foreach ($historyArray as $item) {
                if (!in_array($item->team_buyer_id, [0, $transfer->team_seller_id], true)) {
                    $teamArray[] = $item->team_buyer_id;
                }

                if (!in_array($item->team_seller_id, [0, $transfer->team_seller_id], true)) {
                    $teamArray[] = $item->team_seller_id;
                }
            }

            $historyArray = Loan::find()
                ->where(['season_id' => $seasonId])
                ->andWhere(['not', ['team_buyer_id' => null]])
                ->andWhere(['not', ['team_seller_id' => null]])
                ->andWhere([
                    'or',
                    ['team_seller_id' => $transfer->team_seller_id],
                    ['team_buyer_id' => $transfer->team_seller_id],
                ])
                ->andWhere(['ready' => null])
                ->all();
            foreach ($historyArray as $item) {
                if (!in_array($item->team_buyer_id, [0, $transfer->team_seller_id], true)) {
                    $teamArray[] = $item->team_buyer_id;
                }

                if (!in_array($item->team_seller_id, [0, $transfer->team_seller_id], true)) {
                    $teamArray[] = $item->team_seller_id;
                }
            }

            $userArray = [$transfer->user_seller_id];

            $historyArray = Transfer::find()
                ->where(['season_id' => $seasonId])
                ->andWhere(['not', ['user_buyer_id' => null]])
                ->andWhere(['not', ['user_seller_id' => null]])
                ->andWhere([
                    'or',
                    ['user_seller_id' => $transfer->user_seller_id],
                    ['user_buyer_id' => $transfer->user_seller_id],
                ])
                ->andWhere(['ready' => null])
                ->all();
            foreach ($historyArray as $item) {
                if (!in_array($item->user_buyer_id, [0, $transfer->user_seller_id], true)) {
                    $userArray[] = $item->user_buyer_id;
                }

                if (!in_array($item->user_seller_id, [0, $transfer->user_seller_id], true)) {
                    $userArray[] = $item->user_seller_id;
                }
            }

            $historyArray = Loan::find()
                ->where(['season_id' => $seasonId])
                ->andWhere(['not', ['user_buyer_id' => null]])
                ->andWhere(['not', ['user_seller_id' => null]])
                ->andWhere([
                    'or',
                    ['user_seller_id' => $transfer->user_seller_id],
                    ['user_buyer_id' => $transfer->user_seller_id],
                ])
                ->andWhere(['ready' => null])
                ->all();
            foreach ($historyArray as $item) {
                if (!in_array($item->user_buyer_id, [0, $transfer->user_seller_id], true)) {
                    $userArray[] = $item->user_buyer_id;
                }

                if (!in_array($item->user_seller_id, [0, $transfer->user_seller_id], true)) {
                    $userArray[] = $item->user_seller_id;
                }
            }

            $sold = false;

            /**
             * @var TransferApplication[] $transferApplicationArray
             */
            $transferApplicationArray = TransferApplication::find()
                ->where(['transfer_id' => $transfer->id])
                ->orderBy(['price' => SORT_DESC, 'date' => SORT_ASC])
                ->all();
            foreach ($transferApplicationArray as $transferApplication) {
                if (in_array($transferApplication->team_id, $teamArray, true)) {
                    $transferApplication->deal_reason_id = DealReason::TEAM_LIMIT;
                    $transferApplication->save(true, ['deal_reason_id']);
                    continue;
                }
                if (in_array($transferApplication->user_id, $userArray, true)) {
                    $transferApplication->deal_reason_id = DealReason::MANAGER_LIMIT;
                    $transferApplication->save(true, ['deal_reason_id']);
                    continue;
                }
                if ($transferApplication->user_id === $transfer->userSeller->referrer_user_id || $transferApplication->user->referrer_user_id === $transfer->user_seller_id) {
                    $transferApplication->deal_reason_id = DealReason::MANAGER_LIMIT;
                    $transferApplication->save(true, ['deal_reason_id']);
                    continue;
                }
            }

            $transferApplicationArray = TransferApplication::find()
                ->where([
                    'transfer_id' => $transfer->id,
                    'deal_reason_id' => null
                ])
                ->orderBy(['price' => SORT_DESC, 'date' => SORT_ASC])
                ->all();
            foreach ($transferApplicationArray as $transferApplication) {
                if ($sold) {
                    $transferApplication->deal_reason_id = DealReason::NOT_BEST;
                    $transferApplication->save(true, ['deal_reason_id']);
                    continue;
                }
                /**
                 * @var Team $buyerTeam
                 */
                $buyerTeam = Team::find()
                    ->where(['id' => $transferApplication->team_id])
                    ->limit(1)
                    ->one();
                /**
                 * @var TransferApplication $transferApplication
                 */
                if ($buyerTeam->finance > $transfer->price_seller && 1 === count($transferApplicationArray)) {
                    $transferApplication->price = $transfer->price_seller;
                }
                if (count($transferApplicationArray) > 1 && $transferApplication->id === $transferApplicationArray[0]->id) {
                    $transferApplication->price = $transferApplicationArray[1]->price + 1;
                }
                if ($transferApplication->price > $transferApplication->team->finance) {
                    $transferApplication->deal_reason_id = DealReason::NO_MONEY;
                    $transferApplication->save(true, ['deal_reason_id']);
                    continue;
                }
                if ($transfer->team_seller_id) {
                    /**
                     * @var Team $sellerTeam
                     */
                    $sellerTeam = Team::find()
                        ->where(['id' => $transfer->team_seller_id])
                        ->limit(1)
                        ->one();
                    Finance::log([
                        'finance_text_id' => FinanceText::INCOME_TRANSFER,
                        'player_id' => $transfer->player_id,
                        'team_id' => $transfer->team_seller_id,
                        'value' => $transferApplication->price,
                        'value_after' => $sellerTeam->finance + $transferApplication->price,
                        'value_before' => $sellerTeam->finance,
                    ]);

                    $sellerTeam->finance += $transferApplication->price;
                    $sellerTeam->save(true, ['finance']);
                }

                $schoolPrice = round($transferApplication->price / 100);

                $schoolTeam = Team::find()
                    ->where(['id' => $transfer->player->school_team_id])
                    ->limit(1)
                    ->one();

                Finance::log([
                    'finance_text_id' => FinanceText::INCOME_TRANSFER_FIRST_TEAM,
                    'player_id' => $transfer->player_id,
                    'team_id' => $transfer->player->school_team_id,
                    'value' => $schoolPrice,
                    'value_after' => $schoolTeam->finance + $schoolPrice,
                    'value_before' => $schoolTeam->finance,
                ]);

                $schoolTeam->finance += $schoolPrice;
                $schoolTeam->save(true, ['finance']);

                Finance::log([
                    'finance_text_id' => FinanceText::OUTCOME_TRANSFER,
                    'player_id' => $transfer->player_id,
                    'team_id' => $transferApplication->team_id,
                    'value' => -$transferApplication->price,
                    'value_after' => $buyerTeam->finance - $transferApplication->price,
                    'value_before' => $buyerTeam->finance,
                ]);

                $buyerTeam->finance -= $transferApplication->price;
                $buyerTeam->save(true, ['finance']);

                $transfer->player->squad_id = null;
                $transfer->player->date_no_action = time() + 604800;
                $transfer->player->is_no_deal = true;
                $transfer->player->order = 0;
                $transfer->player->team_id = $transferApplication->team_id;
                $transfer->player->save();

                PhysicalChange::deleteAll([
                    'and',
                    ['player_id' => $transfer->player_id],
                    ['>', 'schedule_id', $scheduleQuery],
                ]);

                $transfer->age = $transfer->player->age;
                $transfer->player_price = $transfer->player->price;
                $transfer->power = $transfer->player->power_nominal;
                $transfer->price_buyer = $transferApplication->price;
                $transfer->ready = time();
                $transfer->season_id = $seasonId;
                $transfer->team_buyer_id = $transferApplication->team_id;
                $transfer->user_buyer_id = $transferApplication->user_id;
                $transfer->save();

                foreach ($transfer->player->playerPositions as $position) {
                    $transferPosition = new TransferPosition();
                    $transferPosition->position_id = $position->position_id;
                    $transferPosition->transfer_id = $transfer->id;
                    $transferPosition->save();
                }

                foreach ($transfer->player->playerSpecials as $special) {
                    $transferSpecial = new TransferSpecial();
                    $transferSpecial->level = $special->level;
                    $transferSpecial->special_id = $special->special_id;
                    $transferSpecial->transfer_id = $transfer->id;
                    $transferSpecial->save();
                }

                History::log([
                    'history_text_id' => HistoryText::PLAYER_TRANSFER,
                    'player_id' => $transfer->player_id,
                    'team_id' => $transfer->team_seller_id,
                    'team_2_id' => $transferApplication->team_id,
                    'value' => $transferApplication->price,
                ]);

                $transferDeleteArray = Transfer::find()
                    ->where(['player_id' => $transfer->player_id, 'ready' => null])
                    ->all();
                foreach ($transferDeleteArray as $transferDelete) {
                    $transferDelete->delete();
                }

                $loanDeleteArray = Loan::find()
                    ->where(['player_id' => $transfer->player_id, 'ready' => null])
                    ->all();
                foreach ($loanDeleteArray as $loadDelete) {
                    $loadDelete->delete();
                }

                if ($transferApplication->is_only_one) {
                    $subQuery = Transfer::find()
                        ->select(['id'])
                        ->where(['ready' => null]);

                    TransferApplication::deleteAll([
                        'team_id' => $transferApplication->team_id,
                        'id' => $subQuery,
                    ]);
                }

                $transferApplication->save(true, ['price']);

                $sold = true;
            }
            if ($transfer->is_to_league && !$sold) {
                $price = round($transfer->player->price / 2);

                $sellerTeam = Team::find()
                    ->where(['id' => $transfer->team_seller_id])
                    ->limit(1)
                    ->one();

                Finance::log([
                    'finance_text_id' => FinanceText::INCOME_TRANSFER,
                    'player_id' => $transfer->player_id,
                    'team_id' => $transfer->team_seller_id,
                    'value' => $price,
                    'value_after' => $sellerTeam->finance + $price,
                    'value_before' => $sellerTeam->finance,
                ]);

                $sellerTeam->finance += $price;
                $sellerTeam->save(true, ['finance']);

                $schoolPrice = round($price / 100);

                $schoolTeam = Team::find()
                    ->where(['id' => $transfer->player->school_team_id])
                    ->limit(1)
                    ->one();

                Finance::log([
                    'finance_text_id' => FinanceText::INCOME_TRANSFER_FIRST_TEAM,
                    'player_id' => $transfer->player_id,
                    'team_id' => $transfer->player->school_team_id,
                    'value' => $schoolPrice,
                    'value_after' => $schoolTeam->finance + $schoolPrice,
                    'value_before' => $schoolTeam->finance,
                ]);

                $schoolTeam->finance += $schoolPrice;
                $schoolTeam->save(true, ['finance']);

                $transfer->player->squad_id = null;
                $transfer->player->date_no_action = time() + 604800;
                $transfer->player->is_no_deal = 1;
                $transfer->player->order = 0;
                $transfer->player->team_id = 0;
                $transfer->player->save();

                $transfer->age = $transfer->player->age;
                $transfer->player_price = $transfer->player->price;
                $transfer->power = $transfer->player->power_nominal;
                $transfer->price_buyer = $price;
                $transfer->ready = time();
                $transfer->season_id = $seasonId;
                $transfer->save();

                foreach ($transfer->player->playerPositions as $position) {
                    $transferPosition = new TransferPosition();
                    $transferPosition->position_id = $position->position_id;
                    $transferPosition->transfer_id = $transfer->id;
                    $transferPosition->save();
                }

                foreach ($transfer->player->playerSpecials as $special) {
                    $transferSpecial = new TransferSpecial();
                    $transferSpecial->level = $special->level;
                    $transferSpecial->special_id = $special->special_id;
                    $transferSpecial->transfer_id = $transfer->id;
                    $transferSpecial->save();
                }

                History::log([
                    'history_text_id' => HistoryText::PLAYER_FREE,
                    'player_id' => $transfer->player_id,
                    'team_id' => $transfer->team_seller_id,
                    'team_2_id' => 0,
                    'value' => $price,
                ]);

                $transferDeleteArray = Transfer::find()
                    ->where(['player_id' => $transfer->player_id, 'ready' => null])
                    ->all();
                foreach ($transferDeleteArray as $transferDelete) {
                    $transferDelete->delete();
                }

                $loanDeleteArray = Loan::find()
                    ->where(['player_id' => $transfer->player_id, 'ready' => null])
                    ->all();
                foreach ($loanDeleteArray as $loadDelete) {
                    $loadDelete->delete();
                }
            }
        }
    }
}
