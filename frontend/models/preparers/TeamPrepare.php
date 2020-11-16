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
                        'asc' => ['date_login' => SORT_ASC],
                        'desc' => ['date_login' => SORT_DESC],
                    ],
                    'user' => [
                        'asc' => ['login' => SORT_ASC],
                        'desc' => ['login' => SORT_DESC],
                    ],
                    'team' => [
                        'asc' => ['name' => SORT_ASC],
                        'desc' => ['name' => SORT_DESC],
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