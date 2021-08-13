<?php

// TODO refactor

/**
 * @var common\models\db\User $model
 */

use yii\helpers\Url;

$link = Url::toRoute(['site/password-restore', 'code' => $model->code], true);

print Yii::t('common', 'mail.password-text.text', [
    'link' => $link,
]);
