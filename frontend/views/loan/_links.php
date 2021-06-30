<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use frontend\components\widgets\LinkBar;

try {
    print LinkBar::widget([
        'items' => [
            [
                'text' => Yii::t('frontend', 'views.loan.links.index'),
                'url' => ['loan/index'],
            ],
            [
                'alias' => [
                    ['loan/view'],
                ],
                'text' => Yii::t('frontend', 'views.loan.links.history'),
                'url' => ['loan/history'],
            ],
        ]
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
