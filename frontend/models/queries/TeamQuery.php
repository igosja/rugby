<?php

// TODO refactor

namespace frontend\models\queries;

use common\models\db\History;
use common\models\db\HistoryText;
use common\models\db\Team;
use yii\db\ActiveQuery;

/**
 * Class TeamQuery
 * @package frontend\models\queries
 */
class TeamQuery
{
    /**
     * @param int $teamId
     * @param int|null $userId
     * @return \common\models\db\Team|null
     */
    public static function getFreeTeamById(int $teamId, int $userId = null): ?Team
    {
        /**
         * @var Team $team
         */
        return Team::find()
            ->select([
                'id',
            ])
            ->andWhere([
                'id' => $teamId,
                'user_id' => 0,
            ])
            ->andWhere([
                'not',
                [
                    'id' => History::find()
                        ->select(['team_id'])
                        ->andWhere(['history_text_id' => HistoryText::USER_MANAGER_TEAM_OUT])
                        ->andWhere(['>', 'date', time() - 2 * 24 * 60 * 90])
                        ->andFilterWhere(['!=', 'user_id', $userId])
                ]
            ])
            ->limit(1)
            ->one();
    }

    /**
     * @param int|null $userId
     * @return \yii\db\ActiveQuery
     */
    public static function getFreeTeamListQuery(int $userId = null): ActiveQuery
    {
        return Team::find()
            ->with([
                'base',
                'baseMedical',
                'basePhysical',
                'baseSchool',
                'baseScout',
                'baseTraining',
                'stadium.city.country',
                'teamRequests',
            ])
            ->joinWith(['base', 'stadium.city'])
            ->where(['!=', 'team.id', 0])
            ->andWhere(['user_id' => 0])
            ->andWhere([
                'not',
                [
                    'team.id' => History::find()
                        ->select(['team_id'])
                        ->andWhere(['history_text_id' => HistoryText::USER_MANAGER_TEAM_OUT])
                        ->andWhere(['>', 'date', time() - 2 * 24 * 60 * 90])
                        ->andFilterWhere(['!=', 'user_id', $userId])
                ]
            ]);
    }

    /**
     * @param int $teamId
     * @return Team|null
     */
    public static function getTeamById(int $teamId): ?Team
    {
        /**
         * @var Team $team
         */
        $team = Team::find()
            ->where(['id' => $teamId])
            ->limit(1)
            ->one();
        return $team;
    }

    /**
     * @return ActiveQuery
     */
    public static function getTeamGroupByCountryListQuery(): ActiveQuery
    {
        return Team::find()
            ->joinWith(['stadium.city.country'], false)
            ->select(
                [
                    'player_number' => 'COUNT(team.id)',
                    'stadium_id',
                ]
            )
            ->where(['!=', 'team.id', 0])
            ->orderBy(['country.name' => SORT_ASC])
            ->groupBy(['country.id']);
    }

    /**
     * @param int $userId
     * @return array
     */
    public static function getTeamListByUserId(int $userId): array
    {
        return Team::find()
            ->where(
                [
                    'or',
                    ['user_id' => $userId],
                    ['vice_user_id' => $userId],
                ]
            )
            ->andWhere(['!=', 'id', 0])
            ->indexBy('id')
            ->all();
    }

    /**
     * @param int $countryId
     * @return ActiveQuery
     */
    public static function getTeamListQuery(int $countryId): ActiveQuery
    {
        return Team::find()
            ->joinWith(['user', 'stadium.city'], false)
            ->with(['user', 'stadium.city'])
            ->where(['city.country_id' => $countryId]);
    }
}
