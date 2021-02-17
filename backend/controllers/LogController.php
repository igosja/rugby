<?php

// TODO refactor

namespace backend\controllers;

use backend\models\search\LogSearch;
use common\models\db\Log;
use Yii;
use yii\db\Exception;
use yii\web\Response;

/**
 * Class LogController
 * @package backend\controllers
 */
class LogController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new LogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        $this->view->title = 'Logs';
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function actionClear(): Response
    {
        Yii::$app->db->createCommand()->truncateTable(Log::tableName())->execute();
        return $this->redirect(['index']);
    }
}
