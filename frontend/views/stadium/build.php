<?php

// TODO refactor

use common\models\db\Team;
use frontend\models\forms\StadiumIncrease;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var string $message
 * @var StadiumIncrease $stadiumForm
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
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $this->render('//stadium/_links') ?>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $message ?>
    </div>
</div>
<?php $form = ActiveForm::begin(['action' => ['build', 'ok' => true], 'method' => 'get']) ?>
<?= $form->field($stadiumForm, 'capacity')->hiddenInput()->label(false) ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton('Строить', ['class' => 'btn margin']) ?>
        <?= Html::a('Отказаться', ['increase'], ['class' => 'btn margin']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
