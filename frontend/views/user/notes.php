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

print $this->render('_top');

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
                <th>Блокнот менеджера</th>
            </tr>
        </table>
    </div>
</div>
<?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'errorOptions' => [
            'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 xs-text-center notification-error',
            'tag' => 'div'
        ],
        'labelOptions' => ['class' => 'strong'],
        'options' => ['class' => 'row'],
        'template' =>
            '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">{input}</div>
            {error}',
    ],
]) ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        В Вашем личном <span class="strong">блокноте</span> вы можете хранить секретные заметки
        о тактике, командах или игроках, которых хотите купить.
    </div>
</div>
<?= $form->field($model, 'notes')->textarea(['rows' => 7])->label(false) ?>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
