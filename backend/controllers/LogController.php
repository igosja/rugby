<?php

// TODO refactor

namespace backend\controllers;

use backend\models\search\LogSearch;
use Yii;
use yii\web\Response;

/**
 * Class LogController
 * @package backend\controllers
 */
class LogController extends AbstractController
{
    /**
     * @param string $chapter
     * @return string
     */
    private function prepareIndexAction(string $chapter): string
    {
        $searchModel = new LogSearch();
        $dataProvider = $searchModel->search($chapter);

        $this->view->title = 'Logs (' . $chapter . ')';
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('index', [
            'chapter' => $chapter,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     */
    public function actionBackend(): string
    {
        return $this->prepareIndexAction('backend');
    }

    /**
     * @return string
     */
    public function actionConsole(): string
    {
        return $this->prepareIndexAction('console');
    }

    /**
     * @return string
     */
    public function actionFrontend(): string
    {
        return $this->prepareIndexAction('frontend');
    }

    /**
     * @param string $chapter
     * @return Response
     */
    public function actionClear(string $chapter): Response
    {
        if (file_exists(Yii::getAlias('@' . $chapter) . '/runtime/logs/app.log')) {
            unlink(Yii::getAlias('@' . $chapter) . '/runtime/logs/app.log');
        }
        return $this->redirect([$chapter]);
    }
}
