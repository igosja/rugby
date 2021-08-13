<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\Player;
use common\models\db\Season;
use common\models\db\Team;
use Exception;
use Yii;

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
        $insertData = [];
        $seasonId = Season::getCurrentSeason();

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

            $insertData[] = [
                FinanceText::OUTCOME_SALARY,
                $team->id,
                -$salary,
                $team->finance - $salary,
                $team->finance,
                time(),
                $seasonId,
            ];

            $team->finance -= $salary;
            $team->save(true, ['finance']);
        }

        Yii::$app->db->createCommand()->batchInsert(
            Finance::tableName(),
            ['finance_text_id', 'team_id', 'value', 'value_after', 'value_before', 'date', 'season_id'],
            $insertData
        )->execute();
    }
}
