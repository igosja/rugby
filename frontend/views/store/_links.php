<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use frontend\components\widgets\LinkBar;

try {
    print LinkBar::widget([
        'items' => [
            [
                'text' => 'Виртуальный магазин',
                'url' => ['store/index'],
            ],
            [
                'text' => 'Пополнить счет',
                'url' => ['store/payment'],
            ],
            [
                'text' => 'Подарок другу',
                'url' => ['store/send'],
            ],
            [
                'text' => 'История платежей',
                'url' => ['store/history'],
            ],
        ]
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
