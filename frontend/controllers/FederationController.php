<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\Federation;
use common\models\db\LeagueDistribution;
use common\models\db\National;
use common\models\db\NationalType;
use common\models\db\ParticipantLeague;
use frontend\models\preparers\TeamPrepare;
use frontend\models\queries\FederationQuery;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * Class FederationController
 * @package frontend\controllers
 */
class FederationController extends AbstractController
{
    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTeam(int $id): string
    {
        $federation = $this->getFederation($id);
        $dataProvider = TeamPrepare::getFederationTeamDataProvider($federation->country_id);

        $this->setSeoTitle('Команды федерации');
        return $this->render('team', [
            'dataProvider' => $dataProvider,
            'federation' => $federation,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionNational(int $id): string
    {
        $federation = $this->getFederation($id);

        $query = National::find()
            ->where(['federation_id' => $id, 'national_type_id' => NationalType::MAIN])
            ->orderBy(['national_type_id' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->setSeoTitle('Сборные');
        return $this->render('national', [
            'dataProvider' => $dataProvider,
            'federation' => $federation,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionLeague(int $id): string
    {
        $federation = $this->getFederation($id);

        $leagueDistribution = LeagueDistribution::find()
            ->where(['federation_id' => $id])
            ->orderBy(['season_id' => SORT_DESC])
            ->limit(1)
            ->one();

        $teamArray = [];

        /**
         * @var ParticipantLeague[] $seasonArray
         */
        $seasonArray = ParticipantLeague::find()
            ->joinWith(['team.stadium.city.country.federation'])
            ->where(['federation.id' => $id])
            ->groupBy(['season_id'])
            ->orderBy(['season_id' => SORT_DESC])
            ->all();
        foreach ($seasonArray as $season) {
            $teamArray[$season->season_id] = ParticipantLeague::find()
                ->joinWith(['team.stadium.city.country.federation', 'leagueCoefficient'])
                ->where(['federation.id' => $id, 'participant_league.season_id' => $season->season_id])
                ->orderBy(['league_coefficient.point' => SORT_DESC, 'participant_league.stage_in_id' => SORT_DESC])
                ->all();
        }

        $this->setSeoTitle('Лига чемпионов');

        return $this->render('league', [
            'federation' => $federation,
            'leagueDistribution' => $leagueDistribution,
            'teamArray' => $teamArray,
        ]);
    }

    /**
     * @param int $id
     * @return Federation
     * @throws NotFoundHttpException
     */
    private function getFederation(int $id): Federation
    {
        $federation = FederationQuery::getFederationById($id);
        $this->notFound($federation);

        return $federation;
    }
}
