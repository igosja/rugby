<?php

namespace frontend\models\queries;

use common\models\db\TeamRequest;
use yii\db\ActiveQuery;

/**
 * Class TeamRequestQuery
 * @package frontend\models\queries
 */
class TeamRequestQuery
{
    /**
     * @param int $teamRequestId
     * @param int $userId
     * @return bool
     */
    public static function deleteTeamRequest(int $teamRequestId, int $userId): bool
    {
        TeamRequest::deleteAll(
            [
                'id' => $teamRequestId,
                'user_id' => $userId,
            ]
        );
        return true;
    }

    /**
     * @param int $teamId
     * @param int $userId
     * @return TeamRequest|null
     */
    public static function getTeamRequestByIdAndUser(int $teamId, int $userId): ?TeamRequest
    {
        return TeamRequest::find()
            ->select(
                [
                    'id',
                ]
            )
            ->where(
                [
                    'team_id' => $teamId,
                    'user_id' => $userId,
                ]
            )
            ->limit(1)
            ->one();
    }

    /**
     * @param int $userId
     * @return ActiveQuery
     */
    public static function getTeamRequestListQuery(int $userId): ActiveQuery
    {
        return TeamRequest::find()
            ->with(
                [
                    'team' => function (ActiveQuery $query) {
                        return $query->select(
                            [
                                'id',
                                'name',
                                'power_vs',
                                'stadium_id',
                            ]
                        );
                    },
                    'team.stadium' => function (ActiveQuery $query) {
                        return $query->select(
                            [
                                'capacity',
                                'city_id',
                                'id',
                            ]
                        );
                    },
                    'team.stadium.city' => function (ActiveQuery $query) {
                        return $query->select(
                            [
                                'country_id',
                                'id',
                                'name',
                            ]
                        );
                    },
                    'team.stadium.city.country' => function (ActiveQuery $query) {
                        return $query->select(
                            [
                                'id',
                                'name',
                            ]
                        );
                    },
                ]
            )
            ->select(
                [
                    'id',
                    'team_id',
                ]
            )
            ->where(['user_id' => $userId])
            ->orderBy(['date' => SORT_ASC]);
    }
}
