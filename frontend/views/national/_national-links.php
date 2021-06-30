<?php

// TODO refactor


use common\components\helpers\ErrorHelper;
use frontend\components\widgets\LinkBar;

$id = Yii::$app->request->get('id', 1);

try {
    print LinkBar::widget([
        'items' => [
            [
                'text' => Yii::t('frontend', 'views.national.national-links.player'),
                'url' => ['national/view', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.national.national-links.game'),
                'url' => ['national/game', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.national.national-links.event'),
                'url' => ['national/event', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.national.national-links.finance'),
                'url' => ['national/finance', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.national.national-links.achievement'),
                'url' => ['national/achievement', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.national.national-links.trophy'),
                'url' => ['national/trophy', 'id' => $id],
            ],
        ]
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
