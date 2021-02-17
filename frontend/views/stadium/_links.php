<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use frontend\components\widgets\LinkBar;

try {
    print LinkBar::widget([
        'items' => [
            [
                'alias' => [
                    ['stadium/build'],
                ],
                'text' => Yii::t('frontend', 'views.stadium.links.increase'),
                'url' => ['stadium/increase'],
            ],
            [
                'alias' => [
                    ['stadium/destroy'],
                ],
                'text' => Yii::t('frontend', 'views.stadium.links.decrease'),
                'url' => ['stadium/decrease'],
            ],
        ]
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
