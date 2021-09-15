<?php

// TODO refactor

namespace frontend\modules\federation\controllers;

use frontend\models\preparers\TeamPrepare;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class TeamController
 * @package frontend\modules\federation\controllers
 */
class TeamController extends AbstractController
{
    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex(int $id): string
    {
        $federation = $this->getFederation($id);
        $dataProvider = TeamPrepare::getFederationTeamDataProvider($federation->country_id);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.team.title'));
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'federation' => $federation,
        ]);
    }
}
