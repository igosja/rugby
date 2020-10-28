<?php

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
        $seasonId = Yii::$app->request->get('seasonId', $this->season->season_id);
        $count = OffSeason::find()
            ->where(['off_season_season_id' => $seasonId])
            ->count();

        $this->seoTitle('Кубок межсезонья');
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
            ->select(['off_season_season_id'])
            ->groupBy(['off_season_season_id'])
            ->orderBy(['off_season_season_id' => SORT_DESC])
            ->all();
        return ArrayHelper::map($season, 'off_season_season_id', 'off_season_season_id');
    }

    /**
     * @return string
     */
    public function actionTable(): string
    {
        $seasonId = Yii::$app->request->get('seasonId', $this->season->season_id);
        $countryId = Yii::$app->request->get('countryId');

        $query = OffSeason::find()
            ->joinWith(['team.stadium.city'])
            ->where(['off_season_season_id' => $seasonId])
            ->andFilterWhere(['city_country_id' => $countryId])
            ->orderBy(['off_season_place' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeTable'],
            ],
            'query' => $query,
            'sort' => false,
        ]);

        $countryArray = OffSeason::find()
            ->joinWith(['team.stadium.city.country'])
            ->where(['off_season_season_id' => $seasonId])
            ->groupBy(['country_id'])
            ->orderBy(['country_name' => SORT_ASC])
            ->all();
        $countryArray = ArrayHelper::map(
            $countryArray,
            'team.stadium.city.country.country_id',
            'team.stadium.city.country.country_name'
        );

        $this->seoTitle('Турнирная таблица кубка межсезонья');
        return $this->render('table', [
            'countryArray' => $countryArray,
            'countryId' => $countryId,
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
        $seasonId = Yii::$app->request->get('seasonId', $this->season->season_id);

        $statisticType = StatisticType::find()
            ->where(['statistic_type_id' => $id])
            ->limit(1)
            ->one();
        if (!$statisticType) {
            $statisticType = StatisticType::find()
                ->where(['statistic_type_id' => 1])
                ->limit(1)
                ->one();
        }

        if ($statisticType->isTeamChapter()) {
            $query = StatisticTeam::find()
                ->where([
                    'statistic_team_tournament_type_id' => TournamentType::CONFERENCE,
                    'statistic_team_season_id' => $seasonId,
                ])
                ->orderBy([$statisticType->statistic_type_select => $statisticType->statistic_type_order]);
        } else {
            $query = StatisticPlayer::find()
                ->where([
                    'statistic_player_tournament_type_id' => TournamentType::CONFERENCE,
                    'statistic_player_season_id' => $seasonId,
                ])
                ->orderBy([$statisticType->statistic_type_select => $statisticType->statistic_type_order]);
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeTable'],
            ],
            'query' => $query,
            'sort' => false,
        ]);

        $this->seoTitle('Статистика кубка межсезонья');
        return $this->render('statistics', [
            'dataProvider' => $dataProvider,
            'myTeam' => $this->myTeam,
            'seasonId' => $seasonId,
            'statisticType' => $statisticType,
            'statisticTypeArray' => StatisticChapter::selectOptions(),
        ]);
    }
}
