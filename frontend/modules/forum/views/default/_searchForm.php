<?php

// TODO refactor

use yii\helpers\Html;

?>
<?= Html::beginForm(['default/search'], 'get', ['class' => 'form-inline']) ?>
<?= Html::textInput('q', Yii::$app->request->get('q'), ['class' => 'form-control form-small']) ?>
<?= Html::submitButton(Yii::t('frontend', 'modules.forum.views.default.search-form.submit'), ['class' => 'btn']) ?>
<?= Html::endForm() ?>
