<?php

namespace backend\controllers;

use backend\models\search\TeamRequestSearch;
use Yii;

/**
 * Class TeamRequestController
 * @package backend\controllers
 */
class TeamRequestController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new TeamRequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        $this->view->title = 'Team requests';
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
            ]
        );
    }
}
