<?php

// TODO refactor

namespace frontend\models\preparers;

use frontend\models\queries\TeamQuery;
use yii\data\ActiveDataProvider;

/**
 * Class TeamPrepare
 * @package frontend\models\preparers
 */
class TeamPrepare
{
    /**
     * @param int $countryId
     * @return ActiveDataProvider
     */
    public static function getFederationTeamDataProvider(int $countryId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'pagination' => false,
            'query' => TeamQuery::getTeamListQuery($countryId),
            'sort' => [
                'attributes' => [
                    'last_visit' => [
                        'asc' => ['user_date_login' => SORT_ASC],
                        'desc' => ['user_date_login' => SORT_DESC],
                    ],
                    'manager' => [
                        'asc' => ['user_login' => SORT_ASC],
                        'desc' => ['user_login' => SORT_DESC],
                    ],
                    'team' => [
                        'asc' => ['team_name' => SORT_ASC],
                        'desc' => ['team_name' => SORT_DESC],
                    ],
                ],
                'defaultOrder' => ['team' => SORT_ASC],
            ]
        ]);
    }

    /**
     * @return ActiveDataProvider
     */
    public static function getTeamGroupDataProvider(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'pagination' => false,
            'query' => TeamQuery::getTeamGroupByCountryListQuery(),
        ]);
    }
}