<?php

// TODO refactor

namespace backend\controllers;

use common\components\AbstractWebController;
use Yii;
use yii\filters\AccessControl;

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
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if ('en' !== Yii::$app->language) {
            Yii::$app->language = 'en';
        }
        return true;
    }
}
