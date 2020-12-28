<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\ElectionPresident;
use common\models\db\ElectionPresidentApplication;
use common\models\db\ElectionPresidentVote;
use common\models\db\ElectionStatus;
use common\models\db\Federation;
use Exception;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Class PresidentController
 * @package frontend\controllers
 */
class PresidentController extends AbstractController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionApplication()
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $federation = $this->myTeam->stadium->city->country->federation;
        Yii::$app->request->setQueryParams(['id' => $federation->id]);

        if ($federation->president_user_id) {
            return $this->redirect(['team/view']);
        }

        $electionPresident = ElectionPresident::find()
            ->where([
                'federation_id' => $federation->id,
                'election_status_id' => ElectionStatus::OPEN
            ])
            ->count();

        if ($electionPresident) {
            return $this->redirect(['view']);
        }

        $position = Federation::find()
            ->where([
                'or',
                ['president_user_id' => $this->user->id],
                ['vice_user_id' => $this->user->id],
            ])
            ->count();

        if ($position) {
            $this->setErrorFlash('Можно быть президентом или заместителем президента только в одной федерации.');
            return $this->redirect(['team/view']);
        }

        $electionPresident = ElectionPresident::find()
            ->where([
                'federation_id' => $federation->id,
                'election_status_id' => ElectionStatus::CANDIDATES,
            ])
            ->limit(1)
            ->one();
        if (!$electionPresident) {
            $electionPresident = new ElectionPresident();
            $electionPresident->federation_id = $federation->id;
            $electionPresident->save();
        }

        $model = ElectionPresidentApplication::find()
            ->where([
                'election_president_id' => $electionPresident->id,
                'user_id' => $this->user->id,
            ])
            ->limit(1)
            ->one();
        if (!$model) {
            $model = new ElectionPresidentApplication();
            $model->election_president_id = $electionPresident->id;
            $model->user_id = $this->user->id;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash();
            return $this->refresh();
        }

        $this->setSeoTitle('Подача заявки на президента федерации');
        return $this->render('application', [
            'federation' => $federation,
            'model' => $model,
        ]);
    }

    /**
     * @return Response
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDeleteApplication(): Response
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $federation = $this->myTeam->stadium->city->country->federation;
        Yii::$app->request->setQueryParams(['id' => $federation->id]);

        if ($federation->president_user_id) {
            return $this->redirect(['team/view']);
        }

        $electionPresident = ElectionPresident::find()
            ->where([
                'federation_id' => $federation->id,
                'election_status_id' => ElectionStatus::OPEN
            ])
            ->count();

        if ($electionPresident) {
            return $this->redirect(['view']);
        }

        $electionPresident = ElectionPresident::find()
            ->where([
                'federation_id' => $federation->id,
                'election_status_id' => ElectionStatus::CANDIDATES,
            ])
            ->limit(1)
            ->one();
        if (!$electionPresident) {
            $electionPresident = new ElectionPresident();
            $electionPresident->federation_id = $federation->id;
            $electionPresident->save();
        }

        $model = ElectionPresidentApplication::find()
            ->where([
                'election_president_id' => $electionPresident->id,
                'user_id' => $this->user->id,
            ])
            ->limit(1)
            ->one();
        if (!$model) {
            return $this->redirect(['application']);
        }

        $model->delete();

        $this->setSuccessFlash('Заявка успешно удалена.');
        return $this->redirect(['application']);
    }

    /**
     * @return string|Response
     */
    public function actionView()
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $federation = $this->myTeam->stadium->city->country->federation;
        Yii::$app->request->setQueryParams(['id' => $federation->id]);

        if ($federation->president_user_id) {
            return $this->redirect(['team/view']);
        }

        $electionPresident = ElectionPresident::find()
            ->where([
                'federation_id' => $federation->id,
                'election_status_id' => ElectionStatus::CANDIDATES
            ])
            ->count();

        if ($electionPresident) {
            return $this->redirect(['application']);
        }

        $electionPresident = ElectionPresident::find()
            ->where([
                'federation_id' => $federation->id,
                'election_status_id' => ElectionStatus::OPEN,
            ])
            ->limit(1)
            ->one();

        if (!$electionPresident) {
            return $this->redirect(['team/view']);
        }

        $voteUser = ElectionPresidentVote::find()
            ->where([
                'election_president_application_id' => ElectionPresidentApplication::find()
                    ->select(['id'])
                    ->where(['election_president_id' => $electionPresident->id]),
                'user_id' => $this->user->id,
            ])
            ->count();
        if (!$voteUser) {
            return $this->redirect(['poll']);
        }

        $this->setSeoTitle('Голосование за президента федерации');
        return $this->render('view', [
            'electionPresident' => $electionPresident,
            'federation' => $federation,
        ]);
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionPoll()
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $federation = $this->myTeam->stadium->city->country->federation;
        Yii::$app->request->setQueryParams(['id' => $federation->country_id]);

        if ($federation->president_user_id) {
            return $this->redirect(['team/view']);
        }

        $electionPresident = ElectionPresident::find()
            ->where([
                'federation_id' => $federation->id,
                'election_status_id' => ElectionStatus::CANDIDATES
            ])
            ->count();

        if ($electionPresident) {
            return $this->redirect(['application']);
        }

        $electionPresident = ElectionPresident::find()
            ->where([
                'federation_id' => $federation->id,
                'election_status_id' => ElectionStatus::OPEN,
            ])
            ->limit(1)
            ->one();

        if (!$electionPresident) {
            return $this->redirect(['team/view']);
        }

        $voteUser = ElectionPresidentVote::find()
            ->where([
                'election_president_application_id' => ElectionPresidentApplication::find()
                    ->select(['id'])
                    ->where(['election_president_id' => $electionPresident->id]),
                'user_id' => $this->user->id,
            ])
            ->count();
        if ($voteUser) {
            return $this->redirect(['view']);
        }

        $model = new ElectionPresidentVote();
        $model->user_id = $this->user->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash('Ваш голос успешно сохранён.');
            return $this->refresh();
        }

        $this->setSeoTitle('Голосование за президента федерации');
        return $this->render('poll', [
            'electionPresident' => $electionPresident,
            'federation' => $federation,
            'model' => $model,
        ]);
    }
}
