<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use common\models\db\Team;
use common\models\db\TeamRequest;
use Exception;
use frontend\models\forms\TeamChangeForm;
use frontend\models\preparers\TeamRequestPrepare;
use frontend\models\queries\TeamQuery;
use frontend\models\queries\TeamRequestQuery;
use Yii;
use yii\base\Action;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Class TeamChangeController
 * @package frontend\controllers
 */
class TeamChangeController extends AbstractController
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
     * @param Action $action
     * @return bool|Response
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws \yii\db\Exception
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (!$this->myTeamArray) {
            return $this->redirect(['team-request/index']);
        }

        return true;
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = TeamRequestPrepare::getFreeTeamDataProvider($this->user->id);
        $myDataProvider = TeamRequestPrepare::getTeamRequestDataProvider($this->user->id);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.team-change.index.title'));
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'myDataProvider' => $myDataProvider,
        ]);
    }

    /**
     * @param null $id
     * @return string|\yii\web\Response
     */
    public function actionConfirm($id = null)
    {
        /**
         * @var Team $team
         */
        $team = TeamQuery::getFreeTeamById($id, $this->user->id);
        if (!$team) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.team-change.confirm.error.team'));
            return $this->redirect(['index']);
        }

        if (TeamRequest::find()->where([
            'team_id' => $id,
            'user_id' => $this->user->id
        ])->count()) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.team-change.confirm.error.request'));
            return $this->redirect(['team/change']);
        }

        $model = new TeamChangeForm();

        $leaveArray = [];
        if (!$this->user->isVip()) {
            if (1 === count($this->myOwnTeamArray)) {
                $leaveArray[$this->myTeam->id] = $this->myTeam->name;
            } else {
                /**
                 * @var Team[] $teamCountryArray
                 */
                $teamCountryArray = Team::find()
                    ->joinWith(['stadium.city.country'])
                    ->where([
                        'country.id' => $team->stadium->city->country->id,
                        'user_id' => $this->user->id,
                    ])
                    ->all();
                if ($teamCountryArray) {
                    foreach ($teamCountryArray as $item) {
                        $leaveArray[$item->id] = $item->name;
                    }
                } else {
                    foreach ($this->myOwnTeamArray as $item) {
                        $leaveArray[$item->id] = $item->name;
                    }
                }
            }
        } else {
            if (1 === count($this->myOwnTeamArray)) {
                if ($team->stadium->city->country->id !== $this->myTeam->stadium->city->country->id) {
                    $leaveArray[0] = Yii::t('frontend', 'controllers.team-change.confirm.additional');
                }
                $leaveArray[$this->myTeam->id] = $this->myTeam->name;
            } else {
                /**
                 * @var Team[] $teamCountryArray
                 */
                $teamCountryArray = Team::find()
                    ->joinWith(['stadium.city.country'])
                    ->where([
                        'country.id' => $team->stadium->city->country->id,
                        'user_id' => $this->user->id,
                    ])
                    ->all();
                if ($teamCountryArray) {
                    foreach ($teamCountryArray as $item) {
                        $leaveArray[$item->id] = $item->name;
                    }
                } else {
                    foreach ($this->myOwnTeamArray as $item) {
                        $leaveArray[$item->id] = $item->name;
                    }
                }
            }
        }

        if (Yii::$app->request->get('ok') && $model->load(Yii::$app->request->post()) && array_key_exists($model->leaveId, $leaveArray)) {
            try {
                $teamAsk = new TeamRequest();
                $teamAsk->team_id = $id;
                $teamAsk->leave_team_id = $model->leaveId;
                $teamAsk->user_id = $this->user->id;
                $teamAsk->save();

                $this->setSuccessFlash(Yii::t('frontend', 'controllers.team-change.confirm.success'));
            } catch (Exception $e) {
                ErrorHelper::log($e);
            }

            return $this->redirect(['index']);
        }

        return $this->render('confirm', [
            'model' => $model,
            'leaveArray' => $leaveArray,
            'team' => $team,
        ]);
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
        $this->setSuccessFlash(Yii::t('frontend', 'controllers.team-change.delete.success'));

        return $this->redirect(['team-request/index']);
    }
}
