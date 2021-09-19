<?php

// TODO refactor

namespace frontend\modules\federation\controllers;

use common\models\db\Vote;
use common\models\db\VoteStatus;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class VoteController
 * @package frontend\modules\federation\controllers
 */
class VoteController extends AbstractController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'create',
                    'delete',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'create',
                            'delete',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param $id
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate($id)
    {
        $federation = $this->getFederation($id);
        if (!in_array($this->user->id, [$federation->president_user_id, $federation->vice_user_id], true)) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.federation.vote-create.error'));
            return $this->redirect(['index', 'id' => $id]);
        }

        $model = new Vote();
        $model->federation_id = $id;
        $model->vote_status_id = VoteStatus::NEW;
        $model->user_id = $this->user->id;

        if ($model->saveVote()) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.federation.vote-create.success'));
            return $this->redirect(['index', 'id' => $id]);
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.vote-create.title'));

        return $this->render('create', [
            'federation' => $federation,
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @param $voteId
     * @return Response
     * @throws \Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function actionDelete($id, $voteId)
    {
        $model = Vote::find()
            ->where(['id' => $voteId, 'federation_id' => $id, 'user_id' => $this->user->id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        $model->delete();

        $this->setSuccessFlash(Yii::t('frontend', 'controllers.federation.vote-delete.success'));
        return $this->redirect(['index', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex(int $id): string
    {
        $statusArray = [VoteStatus::OPEN, VoteStatus::CLOSE];

        $federation = $this->getFederation($id);
        if ($this->user && in_array($this->user->id, [$federation->president_user_id, $federation->vice_user_id], true)) {
            $statusArray[] = VoteStatus::NEW;
        }

        $query = Vote::find()
            ->where(['vote_status_id' => $statusArray, 'federation_id' => $id])
            ->orderBy(['id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeVote'],
            ],
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.vote.title'));

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'federation' => $federation,
        ]);
    }
}
