<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\Team;
use frontend\models\forms\StadiumDecrease;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var StadiumDecrease $model
 * @var Team $team
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//team/_team-top-left', ['team' => $team]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong text-size-1">
                Строительство стадиона
            </div>
        </div>
    </div>
</div>
<?php if ($team->buildingStadium) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert info">
            На стадионе сейчас идет строительство.
            Дата окончания строительства - <?= $team->buildingStadium->endDate() ?>
            <br/>
            <?= Html::a(
                'Отменить строительство',
                ['cancel', 'id' => $team->buildingStadium->id]
            ) ?>
        </div>
    </div>
<?php endif ?>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $this->render('//stadium/_links') ?>
    </div>
</div>
<?php $form = ActiveForm::begin([
    'action' => ['destroy'],
    'fieldConfig' => [
        'errorOptions' => [
            'class' => 'col-lg-4 col-md-4 col-sm-3 col-xs-12 xs-text-center notification-error',
            'tag' => 'div'
        ],
        'options' => ['class' => 'row'],
        'template' =>
            '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">{label}</div>
            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">{input}</div>
            {error}',
    ],
    'method' => 'get',
]) ?>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
        Текушая вместимость
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 strong">
        <?= Yii::$app->formatter->asInteger($team->stadium->capacity) ?>
    </div>
</div>
<?= $form
    ->field($model, 'capacity')
    ->textInput([
        'class' => 'form-control',
        'data' => [
            'current' => $team->stadium->capacity,
            'sit_price' => StadiumDecrease::ONE_SIT_PRICE,
            'url' => Url::to(['format/currency']),
        ],
        'id' => 'stadium-decrease-input',
        'type' => 'integer',
    ])
    ->label('Новая вместимость') ?>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
        Финансы команды
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 strong">
        <?= FormatHelper::asCurrency($team->finance) ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
        Компенсация
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 strong">
        <span id="stadium-decrease-price"><?= FormatHelper::asCurrency(0) ?></span>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton('Уменьшить стадион', ['class' => 'btn margin']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
