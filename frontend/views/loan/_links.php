<?php

use common\components\helpers\ErrorHelper;
use frontend\components\widgets\LinkBar;

try {
    print LinkBar::widget([
        'items' => [
            [
                'text' => 'Игроки на рынке',
                'url' => ['loan/index'],
            ],
            [
                'alias' => [
                    ['loan/view'],
                ],
                'text' => 'Результаты сделок',
                'url' => ['loan/history'],
            ],
        ]
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
