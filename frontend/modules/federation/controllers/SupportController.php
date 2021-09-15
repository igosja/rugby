<?php

// TODO refactor

namespace frontend\modules\federation\controllers;

use yii\filters\AccessControl;

/**
 * Class SupportController
 * @package frontend\modules\federation\controllers
 */
class SupportController extends AbstractController
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
                    'admin',
                    'admin-load',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'admin',
                            'admin-load',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
}
