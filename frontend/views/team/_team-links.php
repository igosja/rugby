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
                'text' => Yii::t('frontend', 'views.team.team-links.player'),
                'url' => ['team/view', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.team.team-links.game'),
                'url' => ['team/game', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.team.team-links.statistics'),
                'url' => ['team/statistics', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.team.team-links.deal'),
                'url' => ['team/deal', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.team.team-links.history'),
                'url' => ['team/history', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.team.team-links.finance'),
                'url' => ['team/finance', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.team.team-links.achievement'),
                'url' => ['team/achievement', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.team.team-links.trophy'),
                'url' => ['team/trophy', 'id' => $id],
            ],
        ]
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
