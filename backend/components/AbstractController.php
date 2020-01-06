<?php

namespace backend\components;

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

        if ('en' != Yii::$app->language) {
            Yii::$app->language = 'en';
        }

//        $allowedIp = [
//            '62.205.148.101',//Peremohy-60
//            '185.38.209.242',//Zhabaeva-7
//            '185.38.209.205',//Zhabaeva-7
//            '31.172.137.26',//Zhabaeva-7
//            '127.0.0.1',
//        ];

//        $userIp = Yii::$app->request->headers->get('x-real-ip');
//        if (!$userIp) {
//            $userIp = Yii::$app->request->userIP;
//        }

//        if (!in_array($userIp, $allowedIp)) {
//            Yii::$app->request->setBaseUrl('');
//            return $this->redirect(['site/index']);
//        }

        return true;
    }
}
