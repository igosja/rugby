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
                            'name' => static function (ActiveQuery $query) {
                                $query->select([
                                    'name_id',
                                    'name_name',
                                ]);
                            },
                            'surname' => static function (ActiveQuery $query) {
                                $query->select([
                                    'surname_id',
                                    'surname_name',
                                ]);
                            },
                        ])
                        ->select([
                            'player_id',
                            'player_name_id',
                            'player_surname_id',
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
                                                    'country' => static function (ActiveQuery $query) {
                                                        $query->select([
                                                            'country_id',
                                                            'country_name',
                                                        ]);
                                                    },
                                                ])
                                                ->select([
                                                    'city_country_id',
                                                    'city_id',
                                                    'city_name',
                                                ]);
                                        },
                                    ])
                                    ->select([
                                        'stadium_city_id',
                                        'stadium_id',
                                    ]);
                            },
                        ])
                        ->select([
                            'team_id',
                            'team_name',
                            'team_stadium_id',
                        ]);
                },
                'user' => static function (ActiveQuery $query) {
                    $query->select([
                        'user_id',
                        'user_login',
                    ]);
                }
            ])
            ->select([
                'history_date',
                'history_history_text_id',
                'history_id',
                'history_player_id',
                'history_team_id',
                'history_user_id',
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
