<?php

// TODO refactor

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
        /**
         * @var TeamRequest|null $teamRequest
         */
        $teamRequest = TeamRequest::find()
            ->andWhere(['team_id' => $teamId, 'user_id' => $userId])
            ->limit(1)
            ->one();
        return $teamRequest;
    }

    /**
     * @param int $userId
     * @return ActiveQuery
     */
    public static function getTeamRequestListQuery(int $userId): ActiveQuery
    {
        return TeamRequest::find()
            ->where(['user_id' => $userId])
            ->orderBy(['date' => SORT_ASC]);
    }
}
