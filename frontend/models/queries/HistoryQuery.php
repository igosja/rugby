<?php

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
            ->with([
                'player' => static function (ActiveQuery $query) {
                    $query
                        ->with([
                            'name',
                            'surname',
                        ]);
                },
                'team' => static function (ActiveQuery $query) {
                    $query
                        ->with([
                            'stadium' => static function (ActiveQuery $query) {
                                $query
                                    ->with([
                                        'city' => static function (ActiveQuery $query) {
                                            $query
                                                ->with([
                                                    'country',
                                                ]);
                                        },
                                    ]);
                            },
                        ]);
                },
                'user',
            ])
            ->where([
                'or',
                ['history_team_id' => $teamId],
                ['history_team_2_id' => $teamId],
            ])
            ->andWhere(['history_season_id' => $seasonId])
            ->orderBy(['history_id' => SORT_DESC]);
    }
}
