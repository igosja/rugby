<?php

// TODO refactor

use common\models\db\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var array $countryArray
 * @var array $dayArray
 * @var User $model
 * @var array $monthArray
 * @var array $sexArray
 * @var array $timeZoneArray
 * @var array $yearArray
 */

print $this->render('//user/_top');

?>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//user/_user-links') ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table">
            <tr>
                <th>Изменение анкетных данных менеджера</th>
            </tr>
        </table>
    </div>
</div>
<?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'errorOptions' => [
            'class' => 'col-lg-4 col-md-3 col-sm-3 col-xs-12 xs-text-center notification-error',
            'tag' => 'div'
        ],
        'labelOptions' => ['class' => 'strong'],
        'options' => ['class' => 'row'],
        'template' =>
            '<div class="col-lg-5 col-md-4 col-sm-4 col-xs-12 text-right xs-text-center">{label}</div>
            <div class="col-lg-3 col-md-5 col-sm-5 col-xs-12">{input}</div>
            {error}',
    ],
]) ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        На этой странице вы можете <span class="strong">изменить свои анкетные данные</span>:
    </div>
</div>
<?= $form->field($model, 'name')->textInput(['class' => 'form-control form-small']) ?>
<?= $form->field($model, 'surname')->textInput(['class' => 'form-control form-small']) ?>
<?= $form->field($model, 'email')->textInput(['class' => 'form-control form-small']) ?>
<?= $form->field($model, 'city')->textInput(['class' => 'form-control form-small']) ?>
<?= $form->field($model, 'country_id')->dropDownList(
    $countryArray,
    ['class' => 'form-control form-small', 'prompt' => 'Не указано']
) ?>
<?= $form->field($model, 'sex_id')->dropDownList(
    $sexArray,
    ['class' => 'form-control form-small']
) ?>
<div class="row">
    <div class="col-lg-5 col-md-4 col-sm-4 col-xs-12 text-right xs-text-center">
        <?= Html::label('Дата рождения', null, ['class' => 'strong']) ?>
    </div>
    <div class="col-lg-3 col-md-5 col-sm-5 col-xs-12">
        <div class="row">
            <?= $form->field($model, 'birth_day', [
                'options' => ['class' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12'],
                'template' => '{input}',
            ])->dropDownList(
                $dayArray,
                ['class' => 'form-control form-small', 'prompt' => '-']
            ) ?>
            <?= $form->field($model, 'birth_month', [
                'options' => ['class' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12'],
                'template' => '{input}',
            ])->dropDownList(
                $monthArray,
                ['class' => 'form-control form-small', 'prompt' => '-']
            ) ?>
            <?= $form->field($model, 'birth_year', [
                'options' => ['class' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12'],
                'template' => '{input}',
            ])->dropDownList(
                $yearArray,
                ['class' => 'form-control form-small', 'prompt' => '-']
            ) ?>
        </div>
    </div>
</div>
<?= $form->field($model, 'timezone')->dropDownList(
    $timeZoneArray,
    ['class' => 'form-control form-small']
) ?>
<?= $form->field($model, 'is_no_vice')->checkbox([], false) ?>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center text-size-3">
        Если вы поменяете свой e-mail, система автоматически отправит письмо на новый адрес с указанием,
        как подтвердить, что ящик принадлежит вам и работает
    </div>
</div>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
