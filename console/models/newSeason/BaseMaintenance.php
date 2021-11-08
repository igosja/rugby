<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\Season;
use common\models\db\Team;
use Exception;

/**
 * Class BaseMaintenance
 * @package console\models\newSeason
 */
class BaseMaintenance
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $seasonId = Season::getCurrentSeason() + 1;

        $teamArray = Team::find()
            ->where(['!=', 'id', 0])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($teamArray as $team) {
            /**
             * @var Team $team
             */
            $maintenance = $team->baseMaintenance();

            Finance::log([
                'finance_text_id' => FinanceText::OUTCOME_MAINTENANCE,
                'season_id' => $seasonId,
                'team_id' => $team->id,
                'value' => -$maintenance,
                'value_after' => $team->finance - $maintenance,
                'value_before' => $team->finance,
            ]);

            $team->finance -= $maintenance;
            $team->save(true, ['finance']);
        }
    }
}
