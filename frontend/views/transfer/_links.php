<?php

// TODO refactor


use common\components\helpers\ErrorHelper;
use frontend\components\widgets\LinkBar;

try {
    print LinkBar::widget([
        'items' => [
            [
                'text' => Yii::t('frontend', 'views.transfer.links.index'),
                'url' => ['transfer/index'],
            ],
            [
                'alias' => [
                    ['transfer/view'],
                ],
                'text' => Yii::t('frontend', 'views.transfer.links.history'),
                'url' => ['transfer/history'],
            ],
        ]
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
