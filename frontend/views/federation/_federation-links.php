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
                'url' => ['federation/team', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.federation.links.national'),
                'url' => ['federation/national', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.federation.links.league'),
                'url' => ['federation/league', 'id' => $id],
            ],
        ]
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
