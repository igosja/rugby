<?php

// TODO refactor

namespace backend\controllers;

use backend\models\search\NewsSearch;
use common\components\helpers\ErrorHelper;
use common\models\db\News;
use Exception;
use Throwable;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class NewsController
 * @package backend\controllers
 */
class NewsController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        $this->view->title = 'News';
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]
        );
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate()
    {
        $model = new News(['user_id' => Yii::$app->user->id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $this->view->title = 'News create';
        $this->view->params['breadcrumbs'][] = ['label' => 'News', 'url' => ['news/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render(
            'create',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id)
    {
        $model = $this->getModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $this->view->title = 'News update';
        $this->view->params['breadcrumbs'][] = ['label' => 'News', 'url' => ['news/index']];
        $this->view->params['breadcrumbs'][] = [
            'label' => $model->title,
            'url' => ['news/view', 'id' => $model->id]
        ];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render(
            'update',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $model = $this->getModel($id);

        $this->view->title = $model->title;
        $this->view->params['breadcrumbs'][] = ['label' => 'News', 'url' => ['news/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render(
            'view',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->getModel($id);

        try {
            if ($model->delete()) {
                $this->setSuccessFlash();
            }
        } catch (Throwable $e) {
            ErrorHelper::log($e);
        }

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return News
     * @throws NotFoundHttpException
     */
    private function getModel(int $id): News
    {
        $model = News::find()
            ->andWhere(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        return $model;
    }
}
