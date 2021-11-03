<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Player;
use common\models\db\Squad;

/**
 * Class PlayerFromNational
 * @package console\models\newSeason
 */
class PlayerFromNational
{
    /**
     * @return void
     */
    public function execute(): void
    {
        Player::updateAll(['national_id' => 0], ['!=', 'national_id', 0]);
        Player::updateAll(['national_squad_id' => Squad::SQUAD_DEFAULT], ['!=', 'national_squad_id', Squad::SQUAD_DEFAULT]);
    }
}
