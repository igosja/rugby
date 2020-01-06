<?php

namespace frontend\models\queries;

use common\models\db\Finance;
use yii\db\ActiveQuery;

/**
 * Class FinanceQuery
 * @package frontend\models\queries
 */
class FinanceQuery
{
    /**
     * @param int $teamId
     * @param int $seasonId
     * @return ActiveQuery
     */
    public static function getTeamFinanceListQuery(int $teamId, int $seasonId): ActiveQuery
    {
        return Finance::find()
            ->select([
                'finance_date',
                'finance_finance_text_id',
                'finance_id',
                'finance_value',
                'finance_value_after',
                'finance_value_before',
            ])
            ->where([
                'finance_team_id' => $teamId,
                'finance_season_id' => $seasonId,
            ])
            ->orderBy(['finance_id' => SORT_DESC]);
    }
}
