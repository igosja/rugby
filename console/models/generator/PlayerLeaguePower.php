<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Player;

/**
 * Class PlayerLeaguePower
 * @package console\models\generator
 */
class PlayerLeaguePower
{
    /**
     * @return void
     */
    public function execute(): void
    {
        Player::updateAll(
            ['power_nominal' => 15],
            ['school_team_id' => 0]
        );
    }
}