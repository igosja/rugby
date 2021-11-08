<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\National;
use common\models\db\Team;

/**
 * Class VisitorResetAll
 * @package console\models\newSeason
 */
class VisitorResetAll
{
    /**
     * @return void
     */
    public function execute(): void
    {
        Team::updateAll(['visitor' => 100]);
        National::updateAll(['visitor' => 100]);
    }
}
