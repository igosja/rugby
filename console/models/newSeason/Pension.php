<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\Finance;
use common\models\FinanceText;
use common\models\History;
use common\models\HistoryText;
use common\models\Player;
use common\models\Special;
use common\models\Squad;
use common\models\Team;
use Throwable;
use yii\db\StaleObjectException;

/**
 * Class Pension
 * @package console\models\newSeason
 */
class Pension
{
    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function execute()
    {
        $playerArray = Player::find()
            ->with(['playerSpecial'])
            ->where(['>=', 'player_age', Player::AGE_READY_FOR_PENSION])
            ->andWhere(['!=', 'player_team_id', 0])
            ->orderBy(['player_id' => SORT_ASC])
            ->each();
        foreach ($playerArray as $player) {
            /**
             * @var Player $player
             */
            History::log([
                'history_history_text_id' => HistoryText::PLAYER_PENSION_GO,
                'history_player_id' => $player->player_id,
                'history_team_id' => $player->player_team_id,
            ]);

            $special = 0;
            foreach ($player->playerSpecial as $playerSpecial) {
                if (Special::IDOL == $playerSpecial->player_special_id) {
                    if (1 == $playerSpecial->player_special_level) {
                        $special = 15;
                    } elseif (2 == $playerSpecial->player_special_level) {
                        $special = 25;
                    } elseif (3 == $playerSpecial->player_special_level) {
                        $special = 40;
                    } else {
                        $special = 50;
                    }
                }
            }

            $price = ceil($player->player_price / 2 + $player->player_price * $special / 100);

            $team = Team::find()
                ->where(['team_id' => $player->player_team_id])
                ->limit(1)
                ->one();

            Finance::log([
                'finance_finance_text_id' => FinanceText::INCOME_PENSION,
                'finance_team_id' => $player->player_team_id,
                'finance_value' => $price,
                'finance_value_after' => $team->team_finance + $price,
                'finance_value_before' => $team->team_finance,
            ]);

            $team->team_finance = $team->team_finance + $price;
            $team->save(true, ['team_finance']);

            $player->player_squad_id = Squad::SQUAD_DEFAULT;
            $player->player_order = 0;
            $player->player_loan_day = 0;
            $player->player_loan_team_id = 0;
            $player->player_team_id = 0;
            $player->save();

            if ($player->transfer) {
                $player->transfer->delete();
            }

            if ($player->loan) {
                $player->loan->delete();
            }
        }
    }
}
