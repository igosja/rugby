<?php

// TODO refactor

namespace backend\controllers;

use common\models\db\Logo;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class PhotoController
 * @package backend\controllers
 */
class PhotoController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $query = Logo::find()
            ->where(['team_id' => null]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->view->title = 'Фото пользователей';
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        /**
         * @var Logo $model
         */
        $model = Logo::find()
            ->where(['id' => $id])
            ->andWhere(['team_id' => null])
            ->limit(1)
            ->one();
        $this->notFound($model);

        $this->view->title = Html::encode($model->user->login);
        $this->view->params['breadcrumbs'][] = ['label' => 'Фото пользователей', 'url' => ['photo/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     * @throws Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function actionAccept(int $id): Response
    {
        /**
         * @var Logo $model
         */
        $model = Logo::find()
            ->where(['id' => $id])
            ->andWhere(['team_id' => null])
            ->limit(1)
            ->one();
        $this->notFound($model);

        $file = Yii::getAlias('@frontend') . '/web/upload/img/user/125/' . $model->user_id . '.png';
        if (file_exists($file)) {
            rename($file, Yii::getAlias('@frontend') . '/web/img/user/125/' . $model->user_id . '.png');
        }

        $model->delete();
        $this->setSuccessFlash();
        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return Response
     * @throws Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function actionDelete(int $id): Response
    {
        $model = Logo::find()
            ->where(['id' => $id])
            ->andWhere(['team_id' => null])
            ->limit(1)
            ->one();
        $this->notFound($model);

        $model->delete();
        $this->setSuccessFlash();
        return $this->redirect(['index']);
    }
}
