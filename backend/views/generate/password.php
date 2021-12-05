<?php

// TODO refactor

/**
 * @var string $password
 */

use yii\helpers\Html;

?>

<div class="site-index">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="page-header"><?= $password ?></h1>
            <?= Html::beginForm(['password'], 'get') ?>
            <div class="form-group">
                <?= Html::label('Length', null, ['class' => 'control-label']) ?>
                <?= Html::textInput('l', Yii::$app->request->get('l', 10), ['class' => 'form-control']) ?>
            </div>
            <div class="form-group">
                <?= Html::label('Special', null, ['class' => 'control-label']) ?>
                <?= Html::checkbox('s', Yii::$app->request->get('s')) ?>
            </div>
            <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => ['btn', 'btn-default']]) ?>
            </div>
            <?= Html::endForm() ?>
        </div>
    </div>
</div>
