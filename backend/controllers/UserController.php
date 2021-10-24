<?php

// TODO refactor

namespace backend\controllers;

use backend\models\search\UserSearch;
use common\models\db\FireReason;
use common\models\db\Payment;
use common\models\db\User;
use common\models\db\UserBlock;
use common\models\db\UserBlockReason;
use common\models\db\UserBlockType;
use common\models\executors\TeamManagerFireExecute;
use Exception;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class UserController
 * @package backend\controllers
 */
class UserController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        $this->view->title = 'Users';
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

        $userArray = User::find()
            ->andWhere(['not', ['id' => [$model->id, 0]]])
            ->all();
        $userArray = ArrayHelper::map($userArray, 'id', 'login');

        $this->view->title = 'User update';
        $this->view->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
        $this->view->params['breadcrumbs'][] = [
            'label' => $model->login,
            'url' => ['view', 'id' => $model->id]
        ];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('update', [
            'model' => $model,
            'userArray' => $userArray,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $model = $this->getModel($id);

        $this->view->title = $model->login;
        $this->view->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionAuth(int $id): Response
    {
        $model = $this->getModel($id);

        Yii::$app->request->setBaseUrl('');
        return $this->redirect(['site/auth', 'code' => $model->code]);
    }

    /**
     * @param int $id
     * @param int $type
     * @return string|\yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionBlock(int $id, int $type)
    {
        $user = $this->getModel($id);

        $model = new UserBlock();
        $model->user_id = $user->id;
        $model->user_block_reason_id = $type;
        if ($model->load(Yii::$app->request->post())) {
            if (UserBlockType::TYPE_SITE === $model->user_block_type_id) {
                $time = 365;
            } else {
                $time = UserBlock::find()
                        ->andWhere(['user_id' => $user->id])
                        ->andWhere(['>=', 'date', time() - 30 * 24 * 60 * 60])
                        ->count() + 1;
            }
            $model->date = $time * 60 * 60 * 24 + time();
            if ($model->save()) {
                if (UserBlockType::TYPE_SITE === $model->user_block_type_id) {
                    foreach ($user->teams as $team) {
                        $service = new TeamManagerFireExecute($team, FireReason::FIRE_REASON_PENALTY);
                        $service->execute();
                    }
                }
                $this->setSuccessFlash();
                return $this->redirect(['view', 'id' => $user->id]);
            }
        }

        $userBlockReasonArray = UserBlockReason::find()->all();
        $userBlockReasonArray = ArrayHelper::map($userBlockReasonArray, 'id', 'text');

        $userBlockTypeArray = UserBlockType::find()->all();
        $userBlockTypeArray = ArrayHelper::map($userBlockTypeArray, 'id', 'text');

        $this->view->title = Html::encode($user->login);
        $this->view->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('block', [
            'userBlockReasonArray' => $userBlockReasonArray,
            'userBlockTypeArray' => $userBlockTypeArray,
            'user' => $user,
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionPay(int $id)
    {
        $user = $this->getModel($id);

        $model = new Payment();
        $model->user_id = $user->id;
        if ($model->pay()) {
            $this->setSuccessFlash();
            return $this->redirect(['view', 'id' => $user->id]);
        }

        $this->view->title = Html::encode($user->login);
        $this->view->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['user/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('pay', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return User
     * @throws NotFoundHttpException
     */
    private function getModel(int $id): User
    {
        $model = User::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        return $model;
    }
}
