<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Player;

/**
 * Class Injury
 * @package console\models\newSeason
 */
class Injury
{
    /**
     * @return void
     */
    public function execute(): void
    {
        Player::updateAll(['is_injury' => false, 'injury_day' => 0], ['is_injury' => true]);
    }
}
