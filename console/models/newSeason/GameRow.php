<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\Player;

/**
 * Class GameRow
 * @package console\models\newSeason
 */
class GameRow
{
    /**
     * @return void
     */
    public function execute()
    {
        Player::updateAll(['player_game_row' => -1], ['!=', 'player_game_row', -1]);
    }
}
