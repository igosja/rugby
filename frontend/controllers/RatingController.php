<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\RatingChapter;
use common\models\db\RatingFederation;
use common\models\db\RatingTeam;
use common\models\db\RatingType;
use common\models\db\RatingUser;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class RatingController
 * @package frontend\controllers
 */
class RatingController extends AbstractController
{
    /**
     * @param int $id
     * @return string
     */
    public function actionIndex(int $id = RatingType::TEAM_POWER): string
    {
        $countryId = Yii::$app->request->get('countryId');
        $countryArray = [];

        $ratingType = RatingType::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        if (!$ratingType) {
            $ratingType = RatingType::find()
                ->where(['id' => RatingType::TEAM_POWER])
                ->limit(1)
                ->one();
        }

        if (RatingChapter::TEAM === $ratingType->rating_chapter_id) {
            $query = RatingTeam::find()
                ->with([
                    'team',
                    'team.base',
                    'team.baseMedical',
                    'team.basePhysical',
                    'team.baseSchool',
                    'team.baseScout',
                    'team.baseTraining',
                    'team.stadium',
                    'team.stadium.city',
                    'team.stadium.city.country',
                ])
                ->joinWith(['team.stadium.city.country'], false)
                ->andFilterWhere(['country_id' => $countryId]);

            $countryArray = RatingTeam::find()
                ->joinWith(['team.stadium.city.country'])
                ->groupBy(['country.id'])
                ->orderBy(['country.name' => SORT_ASC])
                ->all();
            $countryArray = ArrayHelper::map(
                $countryArray,
                'team.stadium.city.country.id',
                'team.stadium.city.country.name'
            );

            $sort = ['team_id' => SORT_ASC];
        } elseif (RatingType::USER_RATING === $id) {
            $query = RatingUser::find()
                ->with(['user']);

            $sort = ['user_id' => SORT_ASC];
        } else {
            $query = RatingFederation::find()
                ->with(['federation.country'])
                ->joinWith(['federation.country'], false);

            $sort = ['country.name' => SORT_ASC];
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeTable'],
            ],
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'val' => [
                        'asc' => ArrayHelper::merge([$ratingType->field => SORT_ASC], $sort),
                        'desc' => ArrayHelper::merge([$ratingType->field => SORT_DESC], $sort),
                    ],
                    'game' => [
                        'asc' => ArrayHelper::merge(['federation.game' => SORT_ASC], $sort),
                        'desc' => ArrayHelper::merge(['federation.game' => SORT_DESC], $sort),
                    ],
                    'auto' => [
                        'asc' => ArrayHelper::merge(['federation.auto' => SORT_ASC], $sort),
                        'desc' => ArrayHelper::merge(['federation.auto' => SORT_DESC], $sort),
                    ],
                    'player_number' => [
                        'asc' => ArrayHelper::merge(['team.player_number' => SORT_ASC], $sort),
                        'desc' => ArrayHelper::merge(['team.player_number' => SORT_DESC], $sort),
                    ],
                    'base' => [
                        'asc' => ArrayHelper::merge(['team.base_id' => SORT_ASC], $sort),
                        'desc' => ArrayHelper::merge(['team.base_id' => SORT_DESC], $sort),
                    ],
                    'training' => [
                        'asc' => ArrayHelper::merge(['team.base_training_id' => SORT_ASC], $sort),
                        'desc' => ArrayHelper::merge(['team.base_training_id' => SORT_DESC], $sort),
                    ],
                    'medical' => [
                        'asc' => ArrayHelper::merge(['team.base_medical_id' => SORT_ASC], $sort),
                        'desc' => ArrayHelper::merge(['team.base_medical_id' => SORT_DESC], $sort),
                    ],
                    'physical' => [
                        'asc' => ArrayHelper::merge(['team.base_physical_id' => SORT_ASC], $sort),
                        'desc' => ArrayHelper::merge(['team.base_physical_id' => SORT_DESC], $sort),
                    ],
                    'school' => [
                        'asc' => ArrayHelper::merge(['team.base_school_id' => SORT_ASC], $sort),
                        'desc' => ArrayHelper::merge(['team.base_school_id' => SORT_DESC], $sort),
                    ],
                    'scout' => [
                        'asc' => ArrayHelper::merge(['team.base_scout_id' => SORT_ASC], $sort),
                        'desc' => ArrayHelper::merge(['team.base_scout_id' => SORT_DESC], $sort),
                    ],
                    'team_name' => [
                        'asc' => ArrayHelper::merge(['team.name' => SORT_ASC], $sort),
                        'desc' => ArrayHelper::merge(['team.name' => SORT_DESC], $sort),
                    ],
                    's_15' => [
                        'asc' => ArrayHelper::merge(['team.power_s_15' => SORT_ASC], $sort),
                        'desc' => ArrayHelper::merge(['team.power_s_15' => SORT_DESC], $sort),
                    ],
                    's_19' => [
                        'asc' => ArrayHelper::merge(['team.power_s_19' => SORT_ASC], $sort),
                        'desc' => ArrayHelper::merge(['team.power_s_19' => SORT_DESC], $sort),
                    ],
                    's_24' => [
                        'asc' => ArrayHelper::merge(['team.power_s_24' => SORT_ASC], $sort),
                        'desc' => ArrayHelper::merge(['team.power_s_24' => SORT_DESC], $sort),
                    ],
                    'base_price' => [
                        'asc' => ArrayHelper::merge(['team.price_base' => SORT_ASC], $sort),
                        'desc' => ArrayHelper::merge(['team.price_base' => SORT_DESC], $sort),
                    ],
                    'player_price' => [
                        'asc' => ArrayHelper::merge(['team.price_player' => SORT_ASC], $sort),
                        'desc' => ArrayHelper::merge(['team.price_player' => SORT_DESC], $sort),
                    ],
                    'stadium_price' => [
                        'asc' => ArrayHelper::merge(['team.price_stadium' => SORT_ASC], $sort),
                        'desc' => ArrayHelper::merge(['team.price_stadium' => SORT_DESC], $sort),
                    ],
                ],
                'defaultOrder' => ['val' => SORT_ASC],
            ],
        ]);

        $this->setSeoTitle('Рейтинги');

        return $this->render('index', [
            'countryId' => $countryId,
            'countryArray' => $countryArray,
            'dataProvider' => $dataProvider,
            'ratingType' => $ratingType,
            'ratingTypeArray' => RatingChapter::selectOptions(),
        ]);
    }
}
