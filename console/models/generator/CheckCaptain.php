<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\Lineup;
use common\models\db\Schedule;
use common\models\db\TournamentType;
use Exception;

/**
 * Class CheckCaptain
 * @package console\models\generator
 */
class CheckCaptain
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        /**
         * @var Schedule $schedule
         */
        $schedule = Schedule::find()
            ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ->limit(1)
            ->one();
        if (TournamentType::NATIONAL === $schedule->tournamentType->id) {
            $groupBy = 'national_id';
        } else {
            $groupBy = 'team_id';
        }
        $lineupArray = Lineup::find()
            ->select([$groupBy, 'game_id'])
            ->where([
                'game_id' => Game::find()
                    ->select(['id'])
                    ->where(['schedule_id' => $schedule->id])
            ])
            ->having('SUM(is_captain)!=1')
            ->groupBy([$groupBy, 'game_id'])
            ->each();
        foreach ($lineupArray as $lineup) {
            /**
             * @var Lineup $lineup
             */
            Lineup::updateAll(
                ['is_captain' => false],
                ['game_id' => $lineup->game_id, $groupBy => $lineup->$groupBy]
            );

            /**
             * @var Lineup $lineupUpdate
             */
            $lineupUpdate = Lineup::find()
                ->where(['game_id' => $lineup->game_id, $groupBy => $lineup->$groupBy])
                ->orderBy('RAND()')
                ->limit(1)
                ->one();
            $lineupUpdate->is_captain = true;
            $lineupUpdate->save(true, ['is_captain']);
        }
    }
}
