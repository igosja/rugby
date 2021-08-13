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
                'text' => Yii::t('frontend', 'views.player.links.view'),
                'url' => ['player/view', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.player.links.history'),
                'url' => ['player/history', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.player.links.deal'),
                'url' => ['player/deal', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.player.links.transfer'),
                'url' => ['player/transfer', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.player.links.loan'),
                'url' => ['player/loan', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.player.links.achievement'),
                'url' => ['player/achievement', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.player.links.trophy'),
                'url' => ['player/trophy', 'id' => $id],
            ],
        ]
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
