<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\History;
use common\models\db\HistoryText;
use common\models\db\Player;
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
    public function execute(): void
    {
        $playerArray = Player::find()
            ->where(['age' => Player::AGE_READY_FOR_PENSION])
            ->andWhere(['!=', 'team_id', 0])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($playerArray as $player) {
            /**
             * @var Player $player
             */
            History::log([
                'history_text_id' => HistoryText::PLAYER_PENSION_SAY,
                'player_id' => $player->id,
                'team_id' => $player->team_id,
            ]);
        }
    }
}
