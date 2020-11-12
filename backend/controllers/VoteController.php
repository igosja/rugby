<?php

// TODO refactor

namespace backend\controllers;

use backend\models\search\VoteSearch;
use common\components\helpers\ErrorHelper;
use common\models\db\Vote;
use common\models\db\VoteAnswer;
use common\models\db\VoteStatus;
use Exception;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class VoteController
 * @package backend\controllers
 */
class VoteController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new VoteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        $this->view->title = 'Votes';
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Vote();
        $model->vote_status_id = VoteStatus::NEW;
        $model->user_id = Yii::$app->user->id;

        try {
            if ($model->saveVote()) {
                $this->setSuccessFlash();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        $this->view->title = 'Create vote';
        $this->view->params['breadcrumbs'][] = ['label' => 'Votes', 'url' => ['vote/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id)
    {
        $model = Vote::find()->where(['id' => $id])->limit(1)->one();
        $this->notFound($model);
        $model->prepareForm();

        if ($model->saveVote()) {
            $this->setSuccessFlash();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $this->view->title = 'Vote update';
        $this->view->params['breadcrumbs'][] = ['label' => 'Votes', 'url' => ['vote/index']];
        $this->view->params['breadcrumbs'][] = [
            'label' => $model->text,
            'url' => ['vote/view', 'id' => $model->id]
        ];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $model = Vote::find()->where(['id' => $id])->limit(1)->one();
        $this->notFound($model);

        $query = VoteAnswer::find()
            ->where(['vote_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->view->title = $model->text;
        $this->view->params['breadcrumbs'][] = ['label' => 'Votes', 'url' => ['vote/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete(int $id): Response
    {
        $model = Vote::find()->where(['id' => $id])->limit(1)->one();
        $this->notFound($model);

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
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionApprove(int $id):Response
    {
        $model = Vote::find()->where(['id' => $id])->limit(1)->one();
        $this->notFound($model);

        if (VoteStatus::NEW !== $model->vote_status_id) {
            return $this->redirect(Yii::$app->request->referrer ?: ['view', 'id' => $id]);
        }

        $model->date = time();
        $model->vote_status_id = VoteStatus::OPEN;
        try {
            $model->save(true, ['date', 'vote_status_id']);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        $this->setSuccessFlash();
        return $this->redirect(Yii::$app->request->referrer ?: ['view', 'id' => $id]);
    }
}
