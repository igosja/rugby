<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Schedule;
use common\models\db\Stage;
use common\models\db\Team;
use common\models\db\TournamentType;

/**
 * Class MoodReset
 * @package console\models\generator
 */
class MoodReset
{
    /**
     * @return void
     */
    public function execute(): void
    {
        $check = Schedule::find()
            ->where('FROM_UNIXTIME(`date`-86400, "%Y-%m-%d")=CURDATE()')
            ->andWhere([
                'stage_id' => Stage::TOUR_1,
                'tournament_type_id' => TournamentType::CHAMPIONSHIP
            ])
            ->limit(1)
            ->one();
        if (!$check) {
            return;
        }

        Team::updateAll(['mood_rest' => 3, 'mood_super' => 3], ['!=', 'id', 0]);
    }
}
