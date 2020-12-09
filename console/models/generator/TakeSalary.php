<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\Player;
use common\models\db\Team;
use Exception;

/**
 * Class TakeSalary
 * @package console\models\generator
 */
class TakeSalary
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
            $salary = Player::find()
                ->where(['team_id' => $team->id, 'loan_team_id' => null])
                ->orWhere(['loan_team_id' => $team->id])
                ->sum('salary');

            Finance::log([
                'finance_text_id' => FinanceText::OUTCOME_SALARY,
                'team_id' => $team->id,
                'value' => -$salary,
                'value_after' => $team->finance - $salary,
                'value_before' => $team->finance,
            ]);

            $team->finance -= $salary;
            $team->save();
        }
    }
}
