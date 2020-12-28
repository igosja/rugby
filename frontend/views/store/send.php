<?php

// TODO refactor

use common\models\db\User;
use frontend\models\forms\UserTransferMoney;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var UserTransferMoney $model
 * @var User $user
 * @var array $userArray
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1>Виртуальный магазин</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong">
        <p class="text-center">Ваш счёт - <?= $user->money ?></p>
    </div>
</div>
<div class="row margin-top-small text-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//store/_links') ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p>
            На этой странице вы можете перевести средства с вашего денежного счета на денежный счет другого менеджера.
        </p>
        <p>
            Обращаем ваше внимание, что Виртуальная Хоккейная Лига - не платежная система,
            поэтому здесь запрещено передавать/продавать/обменивать единицы своего денежного счёта другим лицам
            с целью получить игровую или неигровую выгоду. Переводы могут выполняться только в качестве подарков
            вашим друзьям или в случаях, когда зачисление средств на ваш денежный счет изначально производилось
            несколькими менеджерами, но платеж был один, и теперь его необходимо разбить по реальным плательщикам.
        </p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-center">Заполните форму для перевода денег:</p>
        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'errorOptions' => [
                    'class' => 'col-lg-4 col-md-3 col-sm-3 col-xs-12 xs-text-center notification-error',
                    'tag' => 'div'
                ],
                'options' => ['class' => 'row'],
                'template' =>
                    '<div class="col-lg-5 col-md-4 col-sm-4 col-xs-12 text-right xs-text-center">{label}</div>
                    <div class="col-lg-3 col-md-5 col-sm-5 col-xs-12">{input}</div>
                    {error}',
            ],
        ]) ?>
        <?= $form
            ->field($model, 'userId')
            ->dropDownList($userArray, ['class' => 'form-control', 'prompt' => 'Выберите менеджера'])
            ->label('Менеджер') ?>
        <div class="row">
            <div class="col-lg-5 col-md-4 col-sm-4 col-xs-12 text-right xs-text-center">
                Доступно
            </div>
            <div class="col-lg-3 col-md-5 col-sm-5 col-xs-12">
                <span class="strong"><?= Yii::$app->formatter->asDecimal($user->money, 2) ?></span>
            </div>
        </div>
        <?= $form
            ->field($model, 'sum')
            ->textInput(['class' => 'form-control', 'type' => 'number', 'step' => 0.01])
            ->label('Сумма') ?>
        <p class="text-center">
            <?= Html::submitButton('Перевести', ['class' => 'btn margin']) ?>
        </p>
        <?php ActiveForm::end() ?>
    </div>
</div>