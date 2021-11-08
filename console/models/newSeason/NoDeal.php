<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Player;

/**
 * Class NoDeal
 * @package console\models\newSeason
 */
class NoDeal
{
    /**
     * @return void
     */
    public function execute(): void
    {
        Player::updateAll(['is_no_deal' => false], ['is_no_deal' => true]);
    }
}
