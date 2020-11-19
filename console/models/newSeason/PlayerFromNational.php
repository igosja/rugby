<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\Player;
use common\models\Squad;

/**
 * Class PlayerFromNational
 * @package console\models\newSeason
 */
class PlayerFromNational
{
    /**
     * @return void
     */
    public function execute()
    {
        Player::updateAll(['player_national_id' => 0], ['!=', 'player_national_id', 0]);
        Player::updateAll(['player_national_squad_id' => Squad::SQUAD_DEFAULT], ['!=', 'player_national_squad_id', 0]);
    }
}
