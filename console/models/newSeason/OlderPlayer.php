<?php

namespace console\models\newSeason;

use common\models\Player;

/**
 * Class OlderPlayer
 * @package console\models\newSeason
 */
class OlderPlayer
{
    /**
     * @return void
     */
    public function execute()
    {
        Player::updateAllCounters(
            ['player_age' => 1],
            ['and', ['!=', 'player_team_id', 0], ['<=', 'player_age', Player::AGE_READY_FOR_PENSION]]
        );
        Player::updateAllCounters(
            ['player_age' => 1],
            ['and', ['player_team_id' => 0], ['!=', 'player_age', 18], ['<=', 'player_age', Player::AGE_READY_FOR_PENSION]]
        );
    }
}
