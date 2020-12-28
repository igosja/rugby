<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\ElectionPresidentVice;
use common\models\db\ElectionPresidentViceApplication;
use common\models\db\ElectionPresidentViceVote;
use common\models\db\ElectionStatus;
use common\models\db\Federation;
use Exception;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Class PresidentViceController
 * @package frontend\controllers
 */
class PresidentViceController extends AbstractController
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

        if ($federation->vice_user_id || !$federation->president_user_id) {
            return $this->redirect(['team/view']);
        }

        $electionPresidentVice = ElectionPresidentVice::find()
            ->where([
                'federation_id' => $federation->id,
                'election_status_id' => ElectionStatus::OPEN
            ])
            ->count();

        if ($electionPresidentVice) {
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

        $electionPresidentVice = ElectionPresidentVice::find()
            ->where([
                'federation_id' => $federation->id,
                'election_status_id' => ElectionStatus::CANDIDATES,
            ])
            ->limit(1)
            ->one();
        if (!$electionPresidentVice) {
            $electionPresidentVice = new ElectionPresidentVice();
            $electionPresidentVice->federation_id = $federation->id;
            $electionPresidentVice->save();
        }

        $model = ElectionPresidentViceApplication::find()
            ->where([
                'election_president_vice_id' => $electionPresidentVice->id,
                'user_id' => $this->user->id,
            ])
            ->limit(1)
            ->one();
        if (!$model) {
            $model = new ElectionPresidentViceApplication();
            $model->election_president_vice_id = $electionPresidentVice->id;
            $model->user_id = $this->user->id;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash('Изменения успшено сохранены.');
            return $this->refresh();
        }

        $this->setSeoTitle('Подача заявки на должность заместителя президента федерации');
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

        if ($federation->vice_user_id || !$federation->president_user_id) {
            return $this->redirect(['team/view']);
        }

        $electionPresidentVice = ElectionPresidentVice::find()
            ->where([
                'federation_id' => $federation->id,
                'election_status_id' => ElectionStatus::OPEN
            ])
            ->count();

        if ($electionPresidentVice) {
            return $this->redirect(['view']);
        }

        $electionPresidentVice = ElectionPresidentVice::find()
            ->where([
                'federation_id' => $federation->id,
                'election_status_id' => ElectionStatus::CANDIDATES,
            ])
            ->limit(1)
            ->one();
        if (!$electionPresidentVice) {
            $electionPresidentVice = new ElectionPresidentVice();
            $electionPresidentVice->federation_id = $federation->id;
            $electionPresidentVice->save();
        }

        $model = ElectionPresidentViceApplication::find()
            ->where([
                'election_president_vice_id' => $electionPresidentVice->id,
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

        if ($federation->vice_user_id || !$federation->president_user_id) {
            return $this->redirect(['team/view']);
        }

        $electionPresidentVice = ElectionPresidentVice::find()
            ->where([
                'federation_id' => $federation->id,
                'election_status_id' => ElectionStatus::CANDIDATES
            ])
            ->count();

        if ($electionPresidentVice) {
            return $this->redirect(['application']);
        }

        $electionPresidentVice = ElectionPresidentVice::find()
            ->where([
                'federation_id' => $federation->id,
                'election_status_id' => ElectionStatus::OPEN,
            ])
            ->limit(1)
            ->one();

        if (!$electionPresidentVice) {
            return $this->redirect(['team/view']);
        }

        $voteUser = ElectionPresidentViceVote::find()
            ->where([
                'election_president_vice_application_id' => ElectionPresidentViceApplication::find()
                    ->select(['id'])
                    ->where(['election_president_vice_id' => $electionPresidentVice->id]),
                'user_id' => $this->user->id,
            ])
            ->count();
        if (!$voteUser) {
            return $this->redirect(['poll']);
        }

        $this->setSeoTitle('Голосование за заместителя президента федерации');
        return $this->render('view', [
            'electionPresidentVice' => $electionPresidentVice,
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
        Yii::$app->request->setQueryParams(['id' => $federation->id]);

        if ($federation->vice_user_id || !$federation->president_user_id) {
            return $this->redirect(['team/view']);
        }

        $electionPresidentVice = ElectionPresidentVice::find()
            ->where([
                'federation_id' => $federation->id,
                'election_status_id' => ElectionStatus::CANDIDATES
            ])
            ->count();

        if ($electionPresidentVice) {
            return $this->redirect(['application']);
        }

        $electionPresidentVice = ElectionPresidentVice::find()
            ->where([
                'federation_id' => $federation->id,
                'election_status_id' => ElectionStatus::OPEN,
            ])
            ->limit(1)
            ->one();

        if (!$electionPresidentVice) {
            return $this->redirect(['team/view']);
        }

        $voteUser = ElectionPresidentViceVote::find()
            ->where([
                'election_president_vice_application_id' => ElectionPresidentViceApplication::find()
                    ->select(['id'])
                    ->where(['election_president_vice_id' => $electionPresidentVice->id]),
                'user_id' => $this->user->id,
            ])
            ->count();
        if ($voteUser) {
            return $this->redirect(['view']);
        }

        $model = new ElectionPresidentViceVote();
        $model->user_id = $this->user->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash('Ваш голос успешно сохранён.');
            return $this->refresh();
        }

        $this->setSeoTitle('Голосование за президента федерации');

        return $this->render('poll', [
            'electionPresidentVice' => $electionPresidentVice,
            'federation' => $federation,
            'model' => $model,
        ]);
    }
}
