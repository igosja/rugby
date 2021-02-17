<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use frontend\components\widgets\LinkBar;

try {
    print LinkBar::widget(
        [
            'items' => [
                [
                    'text' => Yii::t('frontend', 'views.site.sign-up-links.link.sign-up'),
                    'url' => ['site/sign-up'],
                ],
                [
                    'alias' => [
                        ['site/password-restore'],
                    ],
                    'text' => Yii::t('frontend', 'views.site.sign-up-links.link.forgot'),
                    'url' => ['site/forgot-password'],
                ],
                [
                    'alias' => [
                        ['site/activation-repeat'],
                    ],
                    'text' => Yii::t('frontend', 'views.site.sign-up-links.link.activation'),
                    'url' => ['site/activation'],
                ],
            ]
        ]
    );
} catch (Exception $e) {
    ErrorHelper::log($e);
}
