<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\DealReason;
use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\History;
use common\models\db\HistoryText;
use common\models\db\Loan;
use common\models\db\LoanApplication;
use common\models\db\LoanPosition;
use common\models\db\LoanSpecial;
use common\models\db\PhysicalChange;
use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\Team;
use common\models\db\Transfer;
use Throwable;
use yii\db\Expression;
use yii\db\StaleObjectException;

/**
 * Class MakeLoan
 * @package console\models\generator
 */
class MakeLoan
{
    /**
     * @return void
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function execute()
    {
        $seasonId = Season::getCurrentSeason();

        $scheduleQuery = Schedule::find()
            ->select(['id'])
            ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ->limit(1);

        $loanArray = Loan::find()
            ->joinWith(['player'])
            ->with(['player'])
            ->where(['ready' => null])
            ->andWhere('`date`<=UNIX_TIMESTAMP()-86400')
            ->orderBy(['price' => SORT_DESC, 'id' => SORT_ASC])
            ->each();
        foreach ($loanArray as $loan) {
            /**
             * @var Loan $loan
             */
            $teamArray = [$loan->team_seller_id];

            /**
             * @var Transfer[] $historyArray
             */
            $historyArray = Transfer::find()
                ->where(['season_id' => $seasonId])
                ->andWhere(['not', ['team_buyer_id' => null]])
                ->andWhere(['not', ['team_seller_id' => null]])
                ->andWhere([
                    'or',
                    ['team_seller_id' => $loan->team_seller_id],
                    ['team_buyer_id' => $loan->team_seller_id],
                ])
                ->andWhere(['not', ['ready' => null]])
                ->all();
            foreach ($historyArray as $item) {
                if (!in_array($item->team_buyer_id, [0, $loan->team_seller_id], true)) {
                    $teamArray[] = $item->team_buyer_id;
                }

                if (!in_array($item->team_seller_id, [0, $loan->team_seller_id], true)) {
                    $teamArray[] = $item->team_seller_id;
                }
            }

            /**
             * @var Loan[] $historyArray
             */
            $historyArray = Loan::find()
                ->where(['season_id' => $seasonId])
                ->andWhere(['not', ['team_buyer_id' => null]])
                ->andWhere(['not', ['team_seller_id' => null]])
                ->andWhere([
                    'or',
                    ['team_seller_id' => $loan->team_seller_id],
                    ['team_buyer_id' => $loan->team_seller_id],
                ])
                ->andWhere(['not', ['ready' => null]])
                ->all();
            foreach ($historyArray as $item) {
                if (!in_array($item->team_buyer_id, [0, $loan->team_seller_id], true)) {
                    $teamArray[] = $item->team_buyer_id;
                }

                if (!in_array($item->team_seller_id, [0, $loan->team_seller_id], true)) {
                    $teamArray[] = $item->team_seller_id;
                }
            }

            $userArray = [$loan->user_seller_id];

            $historyArray = Transfer::find()
                ->where(['season_id' => $seasonId])
                ->andWhere(['not', ['user_buyer_id' => null]])
                ->andWhere(['not', ['user_seller_id' => null]])
                ->andWhere([
                    'or',
                    ['user_buyer_id' => $loan->user_seller_id],
                    ['user_seller_id' => $loan->user_seller_id],
                ])
                ->andWhere(['not', ['ready' => null]])
                ->all();
            foreach ($historyArray as $item) {
                if (!in_array($item->user_buyer_id, [0, $loan->user_seller_id], true)) {
                    $userArray[] = $item->user_buyer_id;
                }

                if (!in_array($item->user_seller_id, [0, $loan->user_seller_id], true)) {
                    $userArray[] = $item->user_seller_id;
                }
            }

            $historyArray = Loan::find()
                ->where(['season_id' => $seasonId])
                ->andWhere(['not', ['user_buyer_id' => null]])
                ->andWhere(['not', ['user_seller_id' => null]])
                ->andWhere([
                    'or',
                    ['user_buyer_id' => $loan->user_seller_id],
                    ['user_seller_id' => $loan->user_seller_id],
                ])
                ->andWhere(['not', ['ready' => null]])
                ->all();
            foreach ($historyArray as $item) {
                if (!in_array($item->user_buyer_id, [0, $loan->user_seller_id], true)) {
                    $userArray[] = $item->user_buyer_id;
                }

                if (!in_array($item->user_seller_id, [0, $loan->user_seller_id], true)) {
                    $userArray[] = $item->user_seller_id;
                }
            }

            $sold = false;

            /**
             * @var LoanApplication[] $loanApplicationArray
             */
            $loanApplicationArray = LoanApplication::find()
                ->where(['loan_id' => $loan->id])
                ->orderBy(new Expression('price*day DESC, date ASC'))
                ->all();
            foreach ($loanApplicationArray as $loanApplication) {
                if (in_array($loanApplication->team_id, $teamArray, true)) {
                    $loanApplication->deal_reason_id = DealReason::TEAM_LIMIT;
                    $loanApplication->save(true, ['deal_reason_id']);
                    continue;
                }
                if (in_array($loanApplication->user_id, $userArray, true)) {
                    $loanApplication->deal_reason_id = DealReason::MANAGER_LIMIT;
                    $loanApplication->save(true, ['deal_reason_id']);
                    continue;
                }
                if ($loanApplication->user->id === $loan->userSeller->referrer_user_id || $loanApplication->user->referrer_user_id === $loan->user_seller_id) {
                    $loanApplication->deal_reason_id = DealReason::MANAGER_LIMIT;
                    $loanApplication->save(true, ['application_deal_reason_id']);
                    continue;
                }
            }

            $loanApplicationArray = LoanApplication::find()
                ->where(['loan_id' => $loan->id, 'deal_reason_id' => null])
                ->orderBy(new Expression('price*day DESC, date ASC'))
                ->all();
            foreach ($loanApplicationArray as $loanApplication) {
                if ($sold) {
                    $loanApplication->deal_reason_id = DealReason::NOT_BEST;
                    $loanApplication->save(true, ['deal_reason_id']);
                    continue;
                }

                $price = $loanApplication->price * $loanApplication->day;

                $buyerTeam = Team::find()
                    ->where(['id' => $loanApplication->team_id])
                    ->limit(1)
                    ->one();
                /**
                 * @var LoanApplication $loanApplication
                 */
                if ($buyerTeam->finance > $loan->price_seller * $loanApplication->day && 1 === count($loanApplicationArray)) {
                    $loanApplication->price = $loan->price_seller;
                    $price = $loanApplication->price * $loanApplication->day;
                }
                if (count($loanApplicationArray) > 1 && $loanApplication->id === $loanApplicationArray[0]->id) {
                    $newPrice = ceil($loanApplicationArray[1]->price * $loanApplicationArray[1]->day / $loanApplication->day) + 1;
                    if ($loanApplication->price > $newPrice) {
                        $loanApplication->price = $newPrice;
                        $price = $loanApplication->price * $loanApplication->day;
                    }
                }
                if ($price > $buyerTeam->finance) {
                    $loanApplication->deal_reason_id = DealReason::NO_MONEY;
                    $loanApplication->save(true, ['deal_reason_id']);
                    continue;
                }

                $sellerTeam = Team::find()
                    ->where(['id' => $loan->team_seller_id])
                    ->limit(1)
                    ->one();
                Finance::log([
                    'finance_text_id' => FinanceText::INCOME_LOAN,
                    'player_id' => $loan->player_id,
                    'team_id' => $loan->team_seller_id,
                    'value' => $price,
                    'value_after' => $sellerTeam->finance + $price,
                    'value_before' => $sellerTeam->finance,
                ]);

                $sellerTeam->finance += $price;
                $sellerTeam->save(true, ['finance']);

                Finance::log([
                    'finance_text_id' => FinanceText::OUTCOME_LOAN,
                    'player_id' => $loan->player_id,
                    'team_id' => $loanApplication->team_id,
                    'value' => -$price,
                    'value_after' => $buyerTeam->finance - $price,
                    'value_before' => $buyerTeam->finance,
                ]);

                $buyerTeam->finance -= $price;
                $buyerTeam->save(true, ['finance']);

                $loan->player->squad_id = null;
                $loan->player->loan_day = $loanApplication->day;
                $loan->player->order = 0;
                $loan->player->loan_team_id = $loanApplication->team_id;
                $loan->player->save();

                PhysicalChange::deleteAll([
                    'and',
                    ['player_id' => $loan->player_id],
                    ['>', 'schedule_id', $scheduleQuery],
                ]);

                $loan->age = $loan->player->age;
                $loan->day = $loanApplication->day;
                $loan->player_price = $loan->player->price;
                $loan->power = $loan->player->power_nominal;
                $loan->price_buyer = $price;
                $loan->ready = time();
                $loan->season_id = $seasonId;
                $loan->team_buyer_id = $loanApplication->team_id;
                $loan->user_buyer_id = $loanApplication->user_id;
                $loan->save();

                foreach ($loan->player->playerPositions as $position) {
                    $loanPosition = new LoanPosition();
                    $loanPosition->position_id = $position->position_id;
                    $loanPosition->loan_id = $loan->id;
                    $loanPosition->save();
                }

                foreach ($loan->player->playerSpecials as $special) {
                    $loanSpecial = new LoanSpecial();
                    $loanSpecial->level = $special->level;
                    $loanSpecial->special_id = $special->special_id;
                    $loanSpecial->loan_id = $loan->id;
                    $loanSpecial->save();
                }

                History::log([
                    'history_text_id' => HistoryText::PLAYER_LOAN,
                    'player_id' => $loan->player_id,
                    'team_id' => $loan->team_seller_id,
                    'second_team_id' => $loanApplication->team_id,
                    'value' => $price,
                ]);

                $transferDeleteArray = Transfer::find()
                    ->where(['player_id' => $loan->player_id, 'ready' => null])
                    ->all();
                foreach ($transferDeleteArray as $transferDelete) {
                    $transferDelete->delete();
                }

                $loanDeleteArray = Loan::find()
                    ->where(['player_id' => $loan->player_id, 'ready' => null])
                    ->all();
                foreach ($loanDeleteArray as $loadDelete) {
                    $loadDelete->delete();
                }

                if ($loanApplication->is_only_one) {
                    $subQuery = Loan::find()
                        ->select(['id'])
                        ->where(['ready' => null]);

                    LoanApplication::deleteAll([
                        'team_id' => $loanApplication->team_id,
                        'loan_id' => $subQuery,
                    ]);
                }

                $loanApplication->deal_reason_id = null;
                $loanApplication->save(true, ['deal_reason_id', 'price']);

                $sold = true;
            }
        }
    }
}