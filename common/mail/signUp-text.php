<?php

// TODO refactor

/**
 * @var User $model
 */

use common\models\db\User;
use yii\helpers\Html;
use yii\helpers\Url;

$link = Url::toRoute(['site/activation', 'code' => $model->code], true);
$page = Url::toRoute(['site/activation'], true);

print Yii::t('common', 'mail.signUp-text.text', [
    'login' => Html::encode($model->login),
    'link' => $link,
    'code' => $model->code,
    'page' => $page,
]);
