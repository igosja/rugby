<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\Player;

/**
 * Class NoDeal
 * @package console\models\newSeason
 */
class NoDeal
{
    /**
     * @return void
     */
    public function execute()
    {
        Player::updateAll(['player_no_deal' => 0], ['player_no_deal' => 1]);
    }
}
