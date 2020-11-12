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

?>
    Вы успешно зарегистрированы на сайте Виртуальной Регбийной Лиги под логином <?= Html::encode($model->login) ?>.
    Чтобы завершить регистрацию подтвердите свой email по ссылке <?= $link ?> или введите код <?= $model->code ?> на странице <?= $page ?>