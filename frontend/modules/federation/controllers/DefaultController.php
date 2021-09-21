<?php

// TODO refactor

namespace frontend\modules\federation\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Class DefaultController
 * @package frontend\modules\federation\controllers
 */
class DefaultController extends AbstractController
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
                    'attitude-president',
                    'fire',
                    'money-transfer',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'attitude-president',
                            'fire',
                            'money-transfer',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionAttitudePresident(int $id): Response
    {
        if (!$this->myTeam) {
            return $this->redirect(['/federation/news/index', 'id' => $id]);
        }

        $federation = $this->getFederation($id);
        if ($federation->president_user_id === $this->user->id) {
            return $this->redirect(['/federation/news/index', 'id' => $id]);
        }

        if (!$this->myTeam->load(Yii::$app->request->post())) {
            return $this->redirect(['/federation/news/index', 'id' => $id]);
        }

        $this->myTeam->save(true, ['president_attitude_id']);
        return $this->redirect(['/federation/news/index', 'id' => $id]);
    }
}
