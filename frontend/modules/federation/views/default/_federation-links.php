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
                'text' => Yii::t('frontend', 'views.federation.links.team'),
                'url' => ['/federation/team/index', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.federation.links.national'),
                'url' => ['/federation/national/index', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.federation.links.news'),
                'url' => ['/federation/news/index', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.federation.links.finance'),
                'url' => ['/federation/finance/index', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.federation.links.vote'),
                'url' => ['/federation/vote/index', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.federation.links.league'),
                'url' => ['/federation/league/index', 'id' => $id],
            ],
        ]
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
