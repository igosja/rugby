<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\History;
use common\models\db\HistoryText;
use common\models\db\Player;
use common\models\db\Special;
use common\models\db\Squad;
use common\models\db\Team;
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
    public function execute(): void
    {
        $playerArray = Player::find()
            ->with(['playerSpecials'])
            ->where(['>=', 'age', Player::AGE_READY_FOR_PENSION])
            ->andWhere(['!=', 'team_id', 0])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($playerArray as $player) {
            /**
             * @var Player $player
             */
            History::log([
                'history_text_id' => HistoryText::PLAYER_PENSION_GO,
                'player_id' => $player->id,
                'team_id' => $player->team_id,
            ]);

            $special = 0;
            foreach ($player->playerSpecials as $playerSpecial) {
                if (Special::IDOL === $playerSpecial->special_id) {
                    if (1 === $playerSpecial->level) {
                        $special = 15;
                    } elseif (2 === $playerSpecial->level) {
                        $special = 25;
                    } elseif (3 === $playerSpecial->level) {
                        $special = 40;
                    } else {
                        $special = 50;
                    }
                }
            }

            $price = ceil($player->price / 2 + $player->price * $special / 100);

            $team = Team::find()
                ->where(['id' => $player->team_id])
                ->limit(1)
                ->one();

            Finance::log([
                'finance_text_id' => FinanceText::INCOME_PENSION,
                'team_id' => $player->team_id,
                'value' => $price,
                'value_after' => $team->finance + $price,
                'value_before' => $team->finance,
            ]);

            $team->finance += $price;
            $team->save(true, ['finance']);

            $player->squad_id = Squad::SQUAD_DEFAULT;
            $player->order = 0;
            $player->loan_day = 0;
            $player->loan_team_id = 0;
            $player->team_id = 0;
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
