<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\Season;
use frontend\models\preparers\TournamentPrepare;
use Yii;

/**
 * Class TournamentController
 * @package frontend\controllers
 */
class TournamentController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $seasonId = Yii::$app->request->get('seasonId', $this->season->id);

        $countryArray = TournamentPrepare::getCountriesWithChampionships($seasonId);
        $tournaments = TournamentPrepare::getTournaments($seasonId);
        $seasonArray = Season::getSeasonArray();

        $this->setSeoTitle(Yii::t('frontend', 'controllers.tournament.index.title'));
        return $this->render('index', [
            'countryArray' => $countryArray,
            'seasonArray' => $seasonArray,
            'seasonId' => $seasonId,
            'tournaments' => $tournaments,
        ]);
    }
}
