<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\ElectionNationalVice;
use common\models\db\ElectionNationalViceApplication;
use common\models\db\ElectionNationalViceVote;
use common\models\db\ElectionStatus;
use common\models\db\Federation;
use common\models\db\National;
use common\models\db\NationalType;
use Exception;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Class NationalElectionViceController
 * @package frontend\controllers
 */
class NationalElectionViceController extends AbstractController
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

        $national = $this->getNational($federation);

        if (!$national) {
            return $this->redirect(['team/view']);
        }

        $electionNationalVice = ElectionNationalVice::find()
            ->where([
                'federation_id' => $federation->id,
                'national_type_id' => $national->national_type_id,
                'election_status_id' => ElectionStatus::OPEN
            ])
            ->count();

        if ($electionNationalVice) {
            return $this->redirect(['view']);
        }

        $position = National::find()
            ->where([
                'or',
                ['user_id' => $this->user->id],
                ['vice_user_id' => $this->user->id],
            ])
            ->count();

        if ($position) {
            $this->setErrorFlash('Можно быть тренером или заместителем тренера только в одной сборной.');
            return $this->redirect(['team/view']);
        }

        $electionNationalVice = ElectionNationalVice::find()
            ->where([
                'federation_id' => $federation->id,
                'national_type_id' => $national->national_type_id,
                'election_status_id' => ElectionStatus::CANDIDATES,
            ])
            ->limit(1)
            ->one();
        if (!$electionNationalVice) {
            $electionNationalVice = new ElectionNationalVice();
            $electionNationalVice->federation_id = $federation->id;
            $electionNationalVice->national_type_id = $national->national_type_id;
            $electionNationalVice->save();
        }

        $model = ElectionNationalViceApplication::find()
            ->where([
                'election_national_vice_id' => $electionNationalVice->id,
                'user_id' => $this->user->id,
            ])
            ->limit(1)
            ->one();
        if (!$model) {
            $model = new ElectionNationalViceApplication();
            $model->election_national_vice_id = $electionNationalVice->id;
            $model->user_id = $this->user->id;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash('Изменения успшено сохранены.');
            return $this->refresh();
        }

        $this->setSeoTitle('Подача заявки на должность заместителя тренера сборной');
        return $this->render('application', [
            'federation' => $federation,
            'model' => $model,
        ]);
    }

    /**
     * @param Federation $federation
     * @return National|null
     */
    private function getNational(Federation $federation): ?National
    {
        for ($i = NationalType::MAIN; $i <= NationalType::U19; $i++) {
            $national = National::find()
                ->where(['national_type_id' => $i, 'federation_id' => $federation->id])
                ->limit(1)
                ->one();

            if (!$national->vice_user_id && $national->user_id) {
                return $national;
            }
        }

        return null;
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

        $national = $this->getNational($federation);

        if (!$national) {
            return $this->redirect(['team/view']);
        }

        $electionNationalVice = ElectionNationalVice::find()
            ->where([
                'federation_id' => $federation->id,
                'national_type_id' => $national->national_type_id,
                'election_status_id' => ElectionStatus::OPEN
            ])
            ->count();

        if ($electionNationalVice) {
            return $this->redirect(['view']);
        }

        $electionNationalVice = ElectionNationalVice::find()
            ->where([
                'federation_id' => $federation->id,
                'national_type_id' => $national->national_type_id,
                'election_status_id' => ElectionStatus::CANDIDATES,
            ])
            ->limit(1)
            ->one();
        if (!$electionNationalVice) {
            $electionNationalVice = new ElectionNationalVice();
            $electionNationalVice->federation_id = $federation->id;
            $electionNationalVice->national_type_id = $national->national_type_id;
            $electionNationalVice->save();
        }

        $model = ElectionNationalViceApplication::find()
            ->where([
                'election_national_vice_id' => $electionNationalVice->id,
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

        $national = $this->getNational($federation);

        if (!$national) {
            return $this->redirect(['team/view']);
        }

        $electionNationalVice = ElectionNationalVice::find()
            ->where([
                'federation_id' => $federation->id,
                'national_type_id' => $national->national_type_id,
                'election_status_id' => ElectionStatus::CANDIDATES
            ])
            ->count();

        if ($electionNationalVice) {
            return $this->redirect(['application']);
        }

        $electionNationalVice = ElectionNationalVice::find()
            ->where([
                'federation_id' => $federation->id,
                'national_type_id' => $national->national_type_id,
                'election_status_id' => ElectionStatus::OPEN,
            ])
            ->limit(1)
            ->one();

        if (!$electionNationalVice) {
            return $this->redirect(['team/view']);
        }

        $voteUser = ElectionNationalViceVote::find()
            ->where([
                'election_national_vice_application_id' => ElectionNationalViceApplication::find()
                    ->select(['id'])
                    ->where(['election_national_vice_id' => $electionNationalVice->id]),
                'user_id' => $this->user->id,
            ])
            ->count();
        if (!$voteUser) {
            return $this->redirect(['poll']);
        }

        $this->setSeoTitle('Голосование за заместителя тренера сборной');

        return $this->render('view', [
            'electionNationalVice' => $electionNationalVice,
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

        $national = $this->getNational($federation);

        if (!$national) {
            return $this->redirect(['team/view']);
        }

        $electionNationalVice = ElectionNationalVice::find()
            ->where([
                'federation_id' => $federation->id,
                'national_type_id' => $national->national_type_id,
                'election_status_id' => ElectionStatus::CANDIDATES
            ])
            ->count();

        if ($electionNationalVice) {
            return $this->redirect(['application']);
        }

        $electionNationalVice = ElectionNationalVice::find()
            ->where([
                'federation_id' => $federation->id,
                'national_type_id' => $national->national_type_id,
                'election_status_id' => ElectionStatus::OPEN,
            ])
            ->limit(1)
            ->one();

        if (!$electionNationalVice) {
            return $this->redirect(['team/view']);
        }

        $voteUser = ElectionNationalViceVote::find()
            ->where([
                'election_national_vice_application_id' => ElectionNationalViceApplication::find()
                    ->select(['id'])
                    ->where(['election_national_vice_id' => $electionNationalVice->id]),
                'user_id' => $this->user->id,
            ])
            ->count();
        if ($voteUser) {
            return $this->redirect(['view']);
        }

        $model = new ElectionNationalViceVote();
        $model->user_id = $this->user->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash('Ваш голос успешно сохранён.');
            return $this->refresh();
        }

        $this->setSeoTitle('Голосование за заместителя тренера сборной');
        return $this->render('poll', [
            'electionNationalVice' => $electionNationalVice,
            'federation' => $federation,
            'model' => $model,
        ]);
    }
}
