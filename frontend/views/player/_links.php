<?php

use common\components\helpers\ErrorHelper;
use frontend\components\widgets\LinkBar;

/**
 * @var int $id
 */

try {
    print LinkBar::widget([
        'items' => [
            [
                'text' => 'Games',
                'url' => ['player/view', 'id' => $id],
            ],
            [
                'text' => 'History',
                'url' => ['player/history', 'id' => $id],
            ],
            [
                'text' => 'Deals',
                'url' => ['player/deal', 'id' => $id],
            ],
            [
                'text' => 'Transfer',
                'url' => ['player/transfer', 'id' => $id],
            ],
            [
                'text' => 'Loan',
                'url' => ['player/loan', 'id' => $id],
            ],
            [
                'text' => 'Achievements',
                'url' => ['player/achievement', 'id' => $id],
            ],
            [
                'text' => 'Trophies',
                'url' => ['player/trophy', 'id' => $id],
            ],
        ]
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
