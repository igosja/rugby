<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\History;
use common\models\db\HistoryText;
use common\models\db\Player;
use Exception;

/**
 * Class LoanDecreaseAndReturn
 * @package console\models\generator
 */
class LoanDecreaseAndReturn
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        Player::updateAllCounters(['loan_day' => -1], ['not', ['loan_team_id' => null]]);

        $playerArray = Player::find()
            ->where(['<=', 'loan_day', 0])
            ->andWhere(['not', ['loan_team_id' => null]])
            ->each();
        foreach ($playerArray as $player) {
            /**
             * @var Player $player
             */
            History::log([
                'history_text_id' => HistoryText::PLAYER_LOAN_BACK,
                'player_id' => $player->id,
                'team_id' => $player->team_id,
                'team_2_id' => $player->loan_team_id,
            ]);
        }

        Player::updateAll(
            ['loan_team_id' => null],
            ['and', ['not', ['loan_team_id' => null]], ['<=', 'loan_day', 0]]
        );
    }
}
