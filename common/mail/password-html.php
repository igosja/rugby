<?php

/**
 * @var common\models\db\User $model
 */

use yii\helpers\Html;
use yii\helpers\Url;

$link = Url::toRoute(['site/password-restore', 'code' => $model->code], true);

?>
    Вы запросили восстановление пароля на сайте Виртуальной Регбийной Лиги.
    <br>
    Чтобы восстановить пароль перейдите по ссылке <?= Html::a($link, $link) ?>