<?php

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use Exception;
use frontend\models\executors\TeamRequestSaveExecutor;
use frontend\models\preparers\TeamRequestPrepare;
use frontend\models\queries\TeamQuery;
use frontend\models\queries\TeamRequestQuery;
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
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'index',
                    'request',
                    'delete',
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'request',
                            'delete',
                        ],
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
            return $this->redirect(['team/view']);
        }

        $dataProvider = TeamRequestPrepare::getFreeTeamDataProvider();
        $myDataProvider = TeamRequestPrepare::getTeamRequestDataProvider($this->user->user_id);

        $this->seoTitle('Получение команды');
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
            return $this->redirect(['team/view']);
        }

        if (!TeamQuery::getFreeTeamById($id)) {
            $this->setErrorFlash('Команда выбрана неправильно');
            return $this->redirect(['team-request/index']);
        }

        if (TeamRequestQuery::getTeamRequestByIdAndUser($id, $this->user->user_id)) {
            $this->setErrorFlash('Вы уже подали заявку на эту команду');
            return $this->redirect(['team-request/index']);
        }

        try {
            (new TeamRequestSaveExecutor($id, $this->user->user_id))->execute();
            $this->setSuccessFlash('Заявка успешно подана');
        } catch (Exception $e) {
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
            return $this->redirect(['team-request/index']);
        }

        TeamRequestQuery::deleteTeamRequest($id, $this->user->user_id);
        $this->setSuccessFlash('Заявка успешно удалена');

        return $this->redirect(['team-request/index']);
    }
}
