<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\User;
use frontend\components\widgets\LinkBar;

$id = Yii::$app->request->get('id', User::ADMIN_USER_ID);

try {
    print LinkBar::widget([
        'items' => [
            [
                'text' => Yii::t('frontend', 'views.user.user-links.view'),
                'url' => ['user/view', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.user.user-links.achievement'),
                'url' => ['user/achievement', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.user.user-links.trophy'),
                'url' => ['user/trophy', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.user.user-links.finance'),
                'url' => ['user/finance', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.user.user-links.money-transfer'),
                'url' => ['user/money-transfer', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.user.user-links.deal'),
                'url' => ['user/deal', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.user.user-links.questionnaire'),
                'url' => ['user/questionnaire', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.user.user-links.holiday'),
                'url' => ['user/holiday', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.user.user-links.password'),
                'url' => ['user/password', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.user.user-links.referral'),
                'url' => ['user/referral', 'id' => $id],
            ],
            [
                'text' => Yii::t('frontend', 'views.user.user-links.notes'),
                'url' => ['user/notes', 'id' => $id],
            ],
        ]
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
