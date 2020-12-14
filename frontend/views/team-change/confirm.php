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
        <h1>Смена команды</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <p>Здесь вы можете подать заявку на смену текущего клуба либо получения дополнительного.</p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-center">
            Вы подаете заяку на управление командой
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
    ->label('Какую команду вы отдаете взамен') ?>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right xs-text-center">
        <?= Html::submitButton('Подать заяку', ['class' => 'btn margin']) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-left xs-text-center">
        <?= Html::a('Вернуться', ['index'], ['class' => 'btn margin']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
