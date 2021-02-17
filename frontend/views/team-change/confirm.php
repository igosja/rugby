<?php

// TODO refactor

/**
 * @var array $leaveArray
 * @var TeamChangeForm $model
 * @var Team $team
 * @var View $this
 */

use common\models\db\Team;
use frontend\models\forms\TeamChangeForm;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h1><?= Yii::t('frontend', 'views.team-change.confirm.h1') ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <p><?= Yii::t('frontend', 'views.team-change.confirm.p.1') ?></p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-center">
            <?= Yii::t('frontend', 'views.team-change.confirm.p.2') ?>
            <span class="strong"><?= $team->name ?></span>.
        </p>
    </div>
</div>
<?php $form = ActiveForm::begin([
    'action' => ['confirm', 'id' => $team->id, 'ok' => 1],
    'fieldConfig' => [
        'errorOptions' => [
            'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center team-logo-file-error notification-error',
            'tag' => 'div'
        ],
        'options' => ['class' => 'row'],
        'template' => '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">{label} {input} {error}</div>',
    ],
    'options' => ['class' => 'form-inline'],
]) ?>
<?= $form
    ->field($model, 'leaveId')
    ->dropDownList($leaveArray, ['class' => 'form-control'])
    ->label(Yii::t('frontend', 'views.team-change.confirm.label.leave')) ?>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right xs-text-center">
        <?= Html::submitButton(Yii::t('frontend', 'views.team-change.confirm.submit'), ['class' => 'btn margin']) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-left xs-text-center">
        <?= Html::a(Yii::t('frontend', 'views.team-change.confirm.link.index'), ['index'], ['class' => 'btn margin']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
