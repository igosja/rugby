<?php

use yii\helpers\Html;

?>
<?= Html::beginForm(['default/search'], 'get', ['class' => 'form-inline']) ?>
<?= Html::textInput('q', Yii::$app->request->get('q'), ['class' => 'form-control form-small']) ?>
<?= Html::submitButton('Поиск', ['class' => 'btn']) ?>
<?= Html::endForm() ?>
