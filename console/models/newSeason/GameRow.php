<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Player;

/**
 * Class GameRow
 * @package console\models\newSeason
 */
class GameRow
{
    /**
     * @return void
     */
    public function execute(): void
    {
        Player::updateAll(['game_row' => -1], ['!=', 'game_row', -1]);
    }
}
