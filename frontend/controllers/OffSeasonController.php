<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\OffSeason;
use common\models\db\StatisticChapter;
use common\models\db\StatisticPlayer;
use common\models\db\StatisticTeam;
use common\models\db\StatisticType;
use common\models\db\TournamentType;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class OffSeasonController
 * @package frontend\controllers
 */
class OffSeasonController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $seasonId = Yii::$app->request->get('seasonId', $this->season->id);
        $count = OffSeason::find()
            ->where(['season_id' => $seasonId])
            ->count();

        $this->setSeoTitle(Yii::t('frontend', 'controllers.off-season.index.title'));
        return $this->render('index', [
            'count' => $count,
            'seasonArray' => $this->getSeasonArray(),
            'seasonId' => $seasonId,
        ]);
    }

    /**
     * @return array
     */
    private function getSeasonArray(): array
    {
        $season = OffSeason::find()
            ->select(['season_id'])
            ->groupBy(['season_id'])
            ->orderBy(['season_id' => SORT_DESC])
            ->all();
        return ArrayHelper::map($season, 'season_id', 'season_id');
    }

    /**
     * @return string
     */
    public function actionTable(): string
    {
        $seasonId = Yii::$app->request->get('seasonId', $this->season->id);
        $federationId = Yii::$app->request->get('federationId');

        $query = OffSeason::find()
            ->joinWith(['team.stadium.city.country.federation'], false)
            ->with(['team.stadium.city.country.federation'])
            ->where(['season_id' => $seasonId])
            ->andFilterWhere(['federation.id' => $federationId])
            ->orderBy(['place' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeTable'],
            ],
            'query' => $query,
            'sort' => false,
        ]);

        $countryArray = OffSeason::find()
            ->joinWith(['team.stadium.city.country'])
            ->where(['season_id' => $seasonId])
            ->groupBy(['country.id'])
            ->orderBy(['country.name' => SORT_ASC])
            ->all();
        $countryArray = ArrayHelper::map(
            $countryArray,
            'team.stadium.city.country.id',
            'team.stadium.city.country.name'
        );

        $this->setSeoTitle(Yii::t('frontend', 'controllers.off-season.table.title'));
        return $this->render('table', [
            'countryArray' => $countryArray,
            'federationId' => $federationId,
            'dataProvider' => $dataProvider,
            'seasonArray' => $this->getSeasonArray(),
            'seasonId' => $seasonId,
        ]);
    }

    /**
     * @param int $id
     * @return string
     */
    public function actionStatistics($id = 1): string
    {
        $seasonId = Yii::$app->request->get('seasonId', $this->season->id);

        $statisticType = StatisticType::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        if (!$statisticType) {
            $statisticType = StatisticType::find()
                ->where(['id' => 1])
                ->limit(1)
                ->one();
        }

        if (1 === $statisticType->statistic_chapter_id) {
            $query = StatisticTeam::find()
                ->where([
                    'tournament_type_id' => TournamentType::OFF_SEASON,
                    'season_id' => $seasonId,
                ])
                ->orderBy([$statisticType->select_field => SORT_DESC]);
        } else {
            $query = StatisticPlayer::find()
                ->where([
                    'tournament_type_id' => TournamentType::OFF_SEASON,
                    'season_id' => $seasonId,
                ])
                ->orderBy([$statisticType->select_field => SORT_DESC]);
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeTable'],
            ],
            'query' => $query,
            'sort' => false,
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.off-season.statistics.title'));
        return $this->render('statistics', [
            'dataProvider' => $dataProvider,
            'myTeam' => $this->myTeam,
            'seasonId' => $seasonId,
            'statisticType' => $statisticType,
            'statisticTypeArray' => StatisticChapter::selectOptions(),
        ]);
    }
}
