<?php

// TODO refactor

/**
 * @var common\models\db\User $model
 */

use yii\helpers\Html;
use yii\helpers\Url;

$link = Url::toRoute(['site/password-restore', 'code' => $model->code], true);

print Yii::t('common', 'mail.password-html.text', [
    'link' => Html::a($link, $link),
]);
