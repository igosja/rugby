<?php

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
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        $this->view->title = 'Новости';
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
        $model = new News();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash();
            return $this->redirect(['news/view', 'id' => $model->news_id]);
        }

        $this->view->title = 'Создание новости';
        $this->view->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['news/index']];
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
     * @throws Exception
     */
    public function actionUpdate($id)
    {
        $model = News::find()->where(['news_id' => $id])->limit(1)->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash();
            return $this->redirect(['news/view', 'id' => $model->news_id]);
        }

        $this->view->title = 'Редактирование новости';
        $this->view->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['news/index']];
        $this->view->params['breadcrumbs'][] = [
            'label' => $model->news_title,
            'url' => ['news/view', 'id' => $model->news_id]
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
    public function actionView($id)
    {
        $model = News::find()->where(['news_id' => $id])->limit(1)->one();
        $this->notFound($model);

        $this->view->title = $model->news_title;
        $this->view->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['news/index']];
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
    public function actionDelete($id)
    {
        $model = News::find()->where(['news_id' => $id])->limit(1)->one();
        $this->notFound($model);

        try {
            if ($model->delete()) {
                $this->setSuccessFlash();
            }
        } catch (Throwable $e) {
            ErrorHelper::log($e);
        }

        return $this->redirect(['news/index']);
    }
}
