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
                'text' => 'Players',
                'url' => ['team/view', 'id' => $id],
            ],
            [
                'text' => 'Games',
                'url' => ['team/game', 'id' => $id],
            ],
            [
                'text' => 'Statistics',
                'url' => ['team/statistics', 'id' => $id],
            ],
            [
                'text' => Yii::t('app', 'frontend.views.team.team-links.deal'),
                'url' => ['team/deal', 'id' => $id],
            ],
            [
                'text' => 'History',
                'url' => ['team/history', 'id' => $id],
            ],
            [
                'text' => 'Finance',
                'url' => ['team/finance', 'id' => $id],
            ],
            [
                'text' => 'Achievements',
                'url' => ['team/achievement', 'id' => $id],
            ],
            [
                'text' => 'Trophies',
                'url' => ['team/trophy', 'id' => $id],
            ],
        ]
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
