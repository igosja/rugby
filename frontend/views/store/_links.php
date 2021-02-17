<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use frontend\components\widgets\LinkBar;

try {
    print LinkBar::widget([
        'items' => [
            [
                'text' => Yii::t('frontend', 'views.store.links.index'),
                'url' => ['store/index'],
            ],
            [
                'text' => Yii::t('frontend', 'views.store.links.payment'),
                'url' => ['store/payment'],
            ],
            [
                'text' => Yii::t('frontend', 'views.store.links.send'),
                'url' => ['store/send'],
            ],
            [
                'text' => Yii::t('frontend', 'views.store.links.history'),
                'url' => ['store/history'],
            ],
        ]
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
