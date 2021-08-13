<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\History;
use common\models\HistoryText;
use common\models\Player;
use Exception;

/**
 * Class PensionInform
 * @package console\models\newSeason
 */
class PensionInform
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $playerArray = Player::find()
            ->where(['player_age' => Player::AGE_READY_FOR_PENSION])
            ->andWhere(['!=', 'player_team_id', 0])
            ->orderBy(['player_id' => SORT_ASC])
            ->each();
        foreach ($playerArray as $player) {
            /**
             * @var Player $player
             */
            History::log([
                'history_history_text_id' => HistoryText::PLAYER_PENSION_SAY,
                'history_player_id' => $player->player_id,
                'history_team_id' => $player->player_team_id,
            ]);
        }
    }
}
