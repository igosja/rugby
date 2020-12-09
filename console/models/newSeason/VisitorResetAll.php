<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\National;
use common\models\Team;

/**
 * Class VisitorResetAll
 * @package console\models\newSeason
 */
class VisitorResetAll
{
    /**
     * @return void
     */
    public function execute()
    {
        Team::updateAll(['team_visitor' => 100]);
        National::updateAll(['national_visitor' => 100]);
    }
}
