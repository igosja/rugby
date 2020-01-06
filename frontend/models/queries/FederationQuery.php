<?php

namespace frontend\models\queries;

use common\models\db\Federation;
use yii\db\ActiveQuery;

/**
 * Class FederationQuery
 * @package frontend\models\queries
 */
class FederationQuery
{
    /**
     * @param int $countryId
     * @return Federation|null
     */
    public static function getFederationByCountryId(int $countryId)
    {
        return Federation::find()
            ->with([
                'country' => function (ActiveQuery $query) {
                    $query
                        ->with([
                            'cities' => function (ActiveQuery $query) {
                                $query
                                    ->with([
                                        'stadiums' => function (ActiveQuery $query) {
                                            $query
                                                ->select([
                                                    'stadium_city_id',
                                                    'stadium_id',
                                                ])
                                                ->with([
                                                    'team' => function (ActiveQuery $query) {
                                                        $query->select([
                                                            'team_stadium_id',
                                                            'team_attitude_president',
                                                        ]);
                                                    }
                                                ]);
                                        }
                                    ])
                                    ->select([
                                        'city_country_id',
                                        'city_id',
                                    ]);
                            }
                        ])
                        ->select([
                            'country_id',
                            'country_name',
                        ]);
                },
                'president' => function (ActiveQuery $query) {
                    $query->select([
                        'user_date_login',
                        'user_id',
                        'user_login',
                    ]);
                },
                'vice' => function (ActiveQuery $query) {
                    $query->select([
                        'user_date_login',
                        'user_id',
                        'user_login',
                    ]);
                },
            ])
            ->select([
                'federation_country_id',
                'federation_finance',
                'federation_president_id',
                'federation_vice_id',
            ])
            ->where(['federation_country_id' => $countryId])
            ->limit(1)
            ->one();
    }
}
