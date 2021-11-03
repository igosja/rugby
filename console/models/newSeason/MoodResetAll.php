<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\National;
use common\models\db\Team;

/**
 * Class MoodResetAll
 * @package console\models\newSeason
 */
class MoodResetAll
{
    /**
     * @return void
     */
    public function execute(): void
    {
        Team::updateAll(['mood_rest' => 2, 'mood_super' => 2]);
        National::updateAll(['mood_rest' => 2, 'mood_super' => 2]);
    }
}

