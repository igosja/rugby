<?php

namespace backend\controllers;

use backend\models\search\RuleSearch;
use common\components\helpers\ErrorHelper;
use common\models\db\Rule;
use Exception;
use Throwable;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class RuleController
 * @package backend\controllers
 */
class RuleController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new RuleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        $this->view->title = 'Rules';
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
        $model = new Rule();

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            $this->setSuccessFlash();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $this->view->title = 'Rule create';
        $this->view->params['breadcrumbs'][] = ['label' => 'Rules', 'url' => ['rule/index']];
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

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            $this->setSuccessFlash();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $this->view->title = 'Rule update';
        $this->view->params['breadcrumbs'][] = ['label' => 'Rules', 'url' => ['rule/index']];
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
     * @return Rule
     * @throws NotFoundHttpException
     */
    private function getModel(int $id): Rule
    {
        $model = Rule::find()
            ->andWhere(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        return $model;
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
        $this->view->params['breadcrumbs'][] = ['label' => 'Rules', 'url' => ['rule/index']];
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
}
