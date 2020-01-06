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
        TeamRequest::deleteAll([
            'team_request_id' => $teamRequestId,
            'team_request_user_id' => $userId,
        ]);
        return true;
    }

    /**
     * @param int $teamId
     * @param int $userId
     * @return TeamRequest|null
     */
    public static function getTeamRequestByIdAndUser(int $teamId, int $userId)
    {
        return TeamRequest::find()
            ->select([
                'team_request_id',
            ])
            ->where([
                'team_request_team_id' => $teamId,
                'team_request_user_id' => $userId,
            ])
            ->limit(1)
            ->one();
    }

    /**
     * @param int $teamId
     * @param int $userId
     * @return TeamRequest|null
     */
    public static function getTeamRequestListQuery(int $userId): ActiveQuery
    {
        return TeamRequest::find()
            ->with([
                'team' => function (ActiveQuery $query) {
                    return $query->select([
                        'team_id',
                        'team_name',
                        'team_power_vs',
                        'team_stadium_id',
                    ]);
                },
                'team.stadium' => function (ActiveQuery $query) {
                    return $query->select([
                        'stadium_capacity',
                        'stadium_city_id',
                        'stadium_id',
                    ]);
                },
                'team.stadium.city' => function (ActiveQuery $query) {
                    return $query->select([
                        'city_country_id',
                        'city_id',
                        'city_name',
                    ]);
                },
                'team.stadium.city.country' => function (ActiveQuery $query) {
                    return $query->select([
                        'country_id',
                        'country_name',
                    ]);
                },
            ])
            ->select([
                'team_request_id',
                'team_request_team_id',
            ])
            ->where(['team_request_user_id' => $userId])
            ->orderBy(['team_request_date' => SORT_ASC]);
    }
}
