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
                'text' => 'Расширить стадион',
                'url' => ['stadium/increase'],
            ],
            [
                'alias' => [
                    ['stadium/destroy'],
                ],
                'text' => 'Уменьшить стадион',
                'url' => ['stadium/decrease'],
            ],
        ]
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
