<?php

/**
 * @var User $model
 */

use common\models\db\User;
use yii\helpers\Html;
use yii\helpers\Url;

$link = Url::toRoute(['site/activation', 'code' => $model->code], true);
$page = Url::toRoute(['site/activation'], true);

?>
    Вы успешно зарегистрированы на сайте Виртуальной Регбийной Лиги под логином
    <strong><?= Html::encode($model->login) ?></strong>.
    <br/>
    Чтобы завершить регистрацию подтвердите свой email по ссылке <?= Html::a($link, $link) ?>
    или введите код <strong><?= $model->code ?></strong> на странице <?= Html::a($page, $page) ?>