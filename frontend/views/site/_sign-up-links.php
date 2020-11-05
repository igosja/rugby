<?php

use common\components\helpers\ErrorHelper;
use frontend\components\widgets\LinkBar;

try {
    print LinkBar::widget(
        [
            'items' => [
                [
                    'text' => 'Регистрация',
                    'url' => ['site/sign-up'],
                ],
                [
                    'alias' => [
                        ['site/password-restore'],
                    ],
                    'text' => 'Забыли пароль?',
                    'url' => ['site/forgot-password'],
                ],
                [
                    'alias' => [
                        ['site/activation-repeat'],
                    ],
                    'text' => 'Активация аккаунта',
                    'url' => ['site/activation'],
                ],
            ]
        ]
    );
} catch (Exception $e) {
    ErrorHelper::log($e);
}
