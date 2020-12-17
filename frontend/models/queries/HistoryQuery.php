<?php

// TODO refactor

namespace frontend\models\queries;

use common\models\db\History;
use yii\db\ActiveQuery;

/**
 * Class HistoryQuery
 * @package frontend\models\queries
 */
class HistoryQuery
{
    /**
     * @param int $teamId
     * @param int $seasonId
     * @return ActiveQuery
     */
    public static function getTeamHistoryListQuery(int $teamId, int $seasonId): ActiveQuery
    {
        return History::find()
            ->where([
                'or',
                ['team_id' => $teamId],
                ['second_team_id' => $teamId],
            ])
            ->andWhere(['season_id' => $seasonId])
            ->orderBy(['id' => SORT_DESC]);
    }
}
