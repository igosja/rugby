<?php

// TODO refactor

namespace backend\controllers;

use backend\models\search\TeamSearch;
use common\models\db\Team;
use Exception;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class TeamController
 * @package backend\controllers
 */
class TeamController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new TeamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        $this->view->title = 'Teams';
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
     * @param int $id
     * @return string|Response
     * @throws Exception
     */
    public function actionUpdate(int $id)
    {
        $model = $this->getModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $this->view->title = 'Team update';
        $this->view->params['breadcrumbs'][] = ['label' => 'Teams', 'url' => ['team/index']];
        $this->view->params['breadcrumbs'][] = [
            'label' => $model->name,
            'url' => ['team/view', 'id' => $model->id]
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

        $this->view->title = $model->name;
        $this->view->params['breadcrumbs'][] = ['label' => 'Teams', 'url' => ['team/index']];
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
     * @return Team
     * @throws NotFoundHttpException
     */
    private function getModel(int $id): Team
    {
        $model = Team::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        return $model;
    }
}
