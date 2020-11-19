<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Team;
use Exception;

/**
 * Class TeamPowerVs
 * @package console\models\generator
 */
class TeamPowerVs
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $teamArray = Team::find()
            ->where(['!=', 'id', 0])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($teamArray as $team) {
            /**
             * @var Team $team
             */
            $team->updatePower();
        }
    }
}