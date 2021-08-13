<?php

// TODO refactor

use common\models\db\ElectionNationalViceApplication;
use common\models\db\Federation;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var ElectionNationalViceApplication $model
 * @var Federation $federation
 */

print $this->render('//federation/_federation', ['federation' => $federation]);

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h4><?= Yii::t('frontend', 'views.national-election-vice.application.h4') ?></h4>
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
    ->label(Yii::t('frontend', 'views.national-election-vice.application.label.text')) ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton(Yii::t('frontend', 'views.national-election-vice.application.submit'), ['class' => 'btn margin']) ?>
        <?php if (!$model->isNewRecord) : ?>
            <?= Html::a(Yii::t('frontend', 'views.national-election-vice.application.link.delete'), ['delete-application'], ['class' => 'btn margin']) ?>
        <?php endif ?>
    </div>
</div>
<?php ActiveForm::end() ?>
