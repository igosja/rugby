<?php

// TODO refactor

namespace frontend\modules\federation\controllers;

use common\models\db\LeagueDistribution;
use common\models\db\ParticipantLeague;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class DefaultController
 * @package frontend\modules\federation\controllers
 */
class LeagueController extends AbstractController
{
    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex(int $id): string
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

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.league.title'));
        return $this->render('index', [
            'federation' => $federation,
            'leagueDistribution' => $leagueDistribution,
            'teamArray' => $teamArray,
        ]);
    }
}
