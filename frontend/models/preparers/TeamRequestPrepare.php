<?php

// TODO refactor

namespace frontend\models\preparers;

use frontend\models\queries\TeamQuery;
use frontend\models\queries\TeamRequestQuery;
use yii\data\ActiveDataProvider;

/**
 * Class TeamRequestPrepare
 * @package frontend\models\preparers
 */
class TeamRequestPrepare
{
    /**
     * @return ActiveDataProvider
     */
    public static function getFreeTeamDataProvider(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'pagination' => false,
            'query' => TeamQuery::getFreeTeamListQuery(),
            'sort' => [
                'attributes' => [
                    'base' => [
                        'asc' => ['base_id' => SORT_ASC, 'team.id' => SORT_ASC],
                        'desc' => ['base_id' => SORT_DESC, 'team.id' => SORT_ASC],
                    ],
                    'country' => [
                        'asc' => ['country_id' => SORT_ASC, 'team.id' => SORT_ASC],
                        'desc' => ['country_id' => SORT_DESC, 'team.id' => SORT_ASC],
                    ],
                    'finance' => [
                        'asc' => ['finance' => SORT_ASC, 'team.id' => SORT_ASC],
                        'desc' => ['finance' => SORT_DESC, 'team.id' => SORT_ASC],
                    ],
                    'stadium' => [
                        'asc' => ['capacity' => SORT_ASC, 'team.id' => SORT_ASC],
                        'desc' => ['capacity' => SORT_DESC, 'team.id' => SORT_ASC],
                    ],
                    'team' => [
                        'asc' => ['name' => SORT_ASC, 'team.id' => SORT_ASC],
                        'desc' => ['name' => SORT_DESC, 'team.id' => SORT_ASC],
                    ],
                    'vs' => [
                        'asc' => ['power_vs' => SORT_ASC, 'team.id' => SORT_ASC],
                        'desc' => ['power_vs' => SORT_DESC, 'team.id' => SORT_ASC],
                    ],
                ],
                'defaultOrder' => ['vs' => SORT_DESC],
            ],
        ]);
    }

    /**
     * @param int $userId
     * @return ActiveDataProvider
     */
    public static function getTeamRequestDataProvider(int $userId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'pagination' => false,
            'query' => TeamRequestQuery::getTeamRequestListQuery($userId),
        ]);
    }
}