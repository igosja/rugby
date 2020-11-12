<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\Federation;
use frontend\models\preparers\TeamPrepare;
use frontend\models\queries\FederationQuery;
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
        $dataProvider = TeamPrepare::getFederationTeamDataProvider($id);

        $this->setSeoTitle('Команды федерации');
        return $this->render('team', [
            'dataProvider' => $dataProvider,
            'federation' => $federation,
        ]);
    }

    /**
     * @param int $id
     * @return Federation
     * @throws NotFoundHttpException
     */
    private function getFederation(int $id): Federation
    {
        $federation = FederationQuery::getFederationByCountryId($id);
        $this->notFound($federation);

        return $federation;
    }
}
