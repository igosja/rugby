<?php

// TODO refactor

namespace backend\controllers;

use common\components\AbstractWebController;
use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * Class AbstractController
 * @package backend\components
 */
abstract class AbstractController extends AbstractWebController
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
     * @param $action
     * @return bool|Response
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if ('en' !== Yii::$app->language) {
            Yii::$app->language = 'en';
        }

        $allowedIp = [
            '127.0.0.1',
            '91.214.85.240',
        ];

        $userIp = Yii::$app->request->headers->get('x-real-ip');
        if (!$userIp) {
            $userIp = Yii::$app->request->userIP;
        }

        if (!in_array($userIp, $allowedIp, true)) {
            Yii::$app->request->setBaseUrl('');
            return $this->redirect(['site/index']);
        }

        return true;
    }
}
