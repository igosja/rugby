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

        $dataProvider = TeamRequestPrepare::getFreeTeamDataProvider();
        $myDataProvider = TeamRequestPrepare::getTeamRequestDataProvider($this->user->id);

        $this->setSeoTitle('Получение команды');
        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
                'myDataProvider' => $myDataProvider,
            ]
        );
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

        if (!TeamQuery::getFreeTeamById($id)) {
            $this->setErrorFlash('Команда выбрана неправильно');
            return $this->redirect(['team-request/index']);
        }

        if (TeamRequestQuery::getTeamRequestByIdAndUser($id, $this->user->id)) {
            $this->setErrorFlash('Вы уже подали заявку на эту команду');
            return $this->redirect(['team-request/index']);
        }

        try {
            (new TeamRequestSaveExecutor($id, $this->user->id))->execute();
            $this->setSuccessFlash('Заявка успешно подана');
        } catch (Exception $e) {
            $this->setErrorFlash('Не удалось подать заявку');
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
        $this->setSuccessFlash('Заявка успешно удалена');

        return $this->redirect(['team-request/index']);
    }
}
