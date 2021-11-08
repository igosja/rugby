<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Player;

/**
 * Class OlderPlayer
 * @package console\models\newSeason
 */
class OlderPlayer
{
    /**
     * @return void
     */
    public function execute(): void
    {
        Player::updateAllCounters(
            ['age' => 1],
            ['and', ['!=', 'team_id', 0], ['<=', 'age', Player::AGE_READY_FOR_PENSION]]
        );
        Player::updateAllCounters(
            ['age' => 1],
            ['and', ['team_id' => 0], ['!=', 'age', 18], ['<=', 'age', Player::AGE_READY_FOR_PENSION]]
        );
    }
}
