<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\Finance;
use common\models\FinanceText;
use common\models\Season;
use common\models\Team;
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
    public function execute()
    {
        $seasonId = Season::getCurrentSeason() + 1;

        $teamArray = Team::find()
            ->where(['!=', 'team_id', 0])
            ->orderBy(['team_id' => SORT_ASC])
            ->each();
        foreach ($teamArray as $team) {
            /**
             * @var Team $team
             */
            $maintenance = $team->baseMaintenance();

            Finance::log([
                'finance_finance_text_id' => FinanceText::OUTCOME_MAINTENANCE,
                'finance_season_id' => $seasonId,
                'finance_team_id' => $team->team_id,
                'finance_value' => -$maintenance,
                'finance_value_after' => $team->team_finance - $maintenance,
                'finance_value_before' => $team->team_finance,
            ]);

            $team->team_finance = $team->team_finance - $maintenance;
            $team->save(true, ['team_finance']);
        }
    }
}
