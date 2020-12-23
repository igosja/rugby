<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use frontend\components\widgets\LinkBar;

/**
 * @var int $id
 */

try {
    print LinkBar::widget([
        'items' => [
            [
                'text' => 'Команды',
                'url' => ['federation/team', 'id' => $id],
            ],
            [
                'text' => 'Сборные',
                'url' => ['federation/national', 'id' => $id],
            ],
            [
                'text' => 'Новости',
                'url' => ['federation/news', 'id' => $id],
            ],
            [
                'text' => 'Фонд',
                'url' => ['federation/finance', 'id' => $id],
            ],
            [
                'text' => 'Опросы',
                'url' => ['federation/vote', 'id' => $id],
            ],
            [
                'text' => 'Лига Чемпионов',
                'url' => ['federation/league', 'id' => $id],
            ],
        ]
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
