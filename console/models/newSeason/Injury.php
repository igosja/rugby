<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\Player;

/**
 * Class Injury
 * @package console\models\newSeason
 */
class Injury
{
    /**
     * @return void
     */
    public function execute()
    {
        Player::updateAll(['player_injury' => 0, 'player_injury_day' => 0], ['player_injury' => 1]);
    }
}
