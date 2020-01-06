<?php

use common\components\helpers\ErrorHelper;
use frontend\components\widgets\LinkBar;

/**
 * @var int $countryId
 */

try {
    print LinkBar::widget([
        'items' => [
            [
                'text' => 'Команды',
                'url' => ['country/team', 'id' => $countryId],
            ],
            [
                'text' => 'Сборные',
                'url' => ['country/national', 'id' => $countryId],
            ],
            [
                'text' => 'Новости',
                'url' => ['country/news', 'id' => $countryId],
            ],
            [
                'text' => 'Фонд',
                'url' => ['country/finance', 'id' => $countryId],
            ],
            [
                'text' => 'Опросы',
                'url' => ['country/poll', 'id' => $countryId],
            ],
            [
                'text' => 'Лига Чемпионов',
                'url' => ['country/league', 'id' => $countryId],
            ],
        ]
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
