<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use Exception;
use frontend\models\executors\TeamRequestSaveExecutor;
use frontend\models\preparers\TeamRequestPrepare;
use frontend\models\queries\TeamQuery;
use frontend\models\queries\TeamRequestQuery;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Class TeamRequestController
 * @package frontend\controllers
 */
class TeamRequestController extends AbstractController
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
     */
    public function actionIndex()
    {
        if ($this->myTeam) {
            return $this->redirectToMyTeam();
        }

        $dataProvider = TeamRequestPrepare::getFreeTeamDataProvider($this->user->id);
        $myDataProvider = TeamRequestPrepare::getTeamRequestDataProvider($this->user->id);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.team-request.index.title'));
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'myDataProvider' => $myDataProvider,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionRequest(int $id): Response
    {
        if ($this->myTeam) {
            return $this->redirectToMyTeam();
        }

        if (!TeamQuery::getFreeTeamById($id, $this->user->id)) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.team-request.request.error.team'));
            return $this->redirect(['team-request/index']);
        }

        if (TeamRequestQuery::getTeamRequestByIdAndUser($id, $this->user->id)) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.team-request.request.error.request'));
            return $this->redirect(['team-request/index']);
        }

        try {
            (new TeamRequestSaveExecutor($id, $this->user->id))->execute();
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.team-request.request.success'));
        } catch (Exception $e) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.team-request.request.error.catch'));
            ErrorHelper::log($e);
        }

        return $this->redirect(['team-request/index']);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionDelete(int $id): Response
    {
        if ($this->myTeam) {
            return $this->redirectToMyTeam();
        }

        TeamRequestQuery::deleteTeamRequest($id, $this->user->id);
        $this->setSuccessFlash(Yii::t('frontend', 'controllers.team-request.delete.success'));

        return $this->redirect(['team-request/index']);
    }
}
