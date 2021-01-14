<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\Vote;
use common\models\db\VoteAnswer;
use common\models\db\VoteStatus;
use common\models\db\VoteUser;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class VoteController
 * @package frontend\controllers
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
                'only' => ['poll'],
                'rules' => [
                    [
                        'actions' => ['poll'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        Vote::updateAll(
            ['vote_status_id' => VoteStatus::CLOSE],
            [
                'and',
                ['vote_status_id' => VoteStatus::OPEN],
                ['<', 'date', time() - 604800]
            ]
        );

        $federationId = [null];
        if ($this->myTeam) {
            $federationId[] = $this->myTeam->stadium->city->country->federation->id;
        }

        $query = Vote::find()
            ->with(['user', 'voteAnswers.voteUsers', 'voteStatus'])
            ->andWhere(['vote_status_id' => [VoteStatus::OPEN, VoteStatus::CLOSE]])
            ->andWhere(['federation_id' => $federationId])
            ->orderBy(['id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeVote'],
            ],
        ]);

        $this->setSeoTitle('Опросы');
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionView(int $id)
    {
        /**
         * @var Vote $vote
         */
        $vote = Vote::find()
            ->with(['voteAnswers.voteUsers'])
            ->andWhere(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($vote);

        if ($this->user) {
            if (VoteStatus::OPEN === $vote->vote_status_id && (!$vote->federation_id || ($this->myTeam && $this->myTeam->stadium->city->country->federation->id === $vote->federation_id))) {
                $voteUser = VoteUser::find()
                    ->andWhere([
                        'vote_answer_id' => VoteAnswer::find()
                            ->select(['id'])
                            ->andWhere(['vote_id' => $id]),
                        'user_id' => $this->user->id,
                    ])
                    ->count();
                if (!$voteUser) {
                    return $this->redirect(['vote', 'id' => $id]);
                }
            }
        }

        $this->setSeoTitle('Опрос');
        return $this->render('view', [
            'vote' => $vote,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionVote(int $id)
    {
        $vote = Vote::find()->andWhere(['id' => $id])->one();
        $this->notFound($vote);

        if (VoteStatus::OPEN !== $vote->vote_status_id) {
            return $this->redirect(['view', 'id' => $id]);
        }

        if ($vote->federation_id && (!$this->myTeam || $this->myTeam->stadium->city->country->federation->id !== $vote->federation_id)) {
            return $this->redirect(['view', 'id' => $id]);
        }

        $voteUser = VoteUser::find()
            ->andWhere([
                'vote_answer_id' => VoteAnswer::find()
                    ->select(['id'])
                    ->andWhere(['vote_id' => $id]),
                'user_id' => $this->user->id,
            ])
            ->count();
        if ($voteUser) {
            return $this->redirect(['view', 'id' => $id]);
        }

        $model = new VoteUser();
        $model->user_id = $this->user->id;
        if ($model->addAnswer()) {
            $this->setSuccessFlash('Ваш голос успешно сохранён');
            return $this->redirect(['view', 'id' => $id]);
        }

        $this->setSeoTitle('Опрос');
        return $this->render('vote', [
            'model' => $model,
            'vote' => $vote,
        ]);
    }
}
