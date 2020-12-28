<?php

// TODO refactor

use common\models\db\ElectionPresidentViceApplication;
use common\models\db\Federation;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var Federation $federation
 * @var ElectionPresidentViceApplication $model
 */

print $this->render('//federation/_federation', ['federation' => $federation]);

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h4>Подача заявки на пост заместителя президента федерации</h4>
    </div>
</div>
<?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'errorOptions' => ['class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 notification-error'],
        'labelOptions' => ['class' => 'strong'],
        'options' => ['class' => 'row'],
        'template' =>
            '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">{label}</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">{input}</div>
            {error}',
    ],
]) ?>
<?= $form
    ->field($model, 'text')
    ->textarea(['rows' => 5])
    ->label('Ваша программа') ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn margin']) ?>
        <?php if (!$model->isNewRecord) : ?>
            <?= Html::a('Удалить', ['delete-application'], ['class' => 'btn margin']) ?>
        <?php endif ?>
    </div>
</div>
<?php ActiveForm::end() ?>
