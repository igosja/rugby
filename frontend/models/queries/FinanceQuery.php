<?php

// TODO refactor

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
            ->where([
                'team_id' => $teamId,
                'season_id' => $seasonId,
            ])
            ->orderBy(['id' => SORT_DESC]);
    }
}
