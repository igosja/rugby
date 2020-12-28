<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\Payment;
use common\models\db\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var string $bonusLine
 * @var Payment $model
 * @var User $user
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1>Пополнение денежного счета</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong">
        <p class="text-center">Ваш счёт - <?= Yii::$app->formatter->asDecimal($user->money, 2) ?></p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $this->render('//store/_links') ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-justify">
            <span class="strong">Денежный счёт менеджера</span> предназначен для приобретения игровых товаров.
            Перед тем, как пополнить свой денежный счёт, посмотрите
            <?= Html::a('в списке игровых товаров', ['store/index']) ?>, какие из них вам нужны.
            Таким образом, вы сможете рассчитать, сколько для этого потребуется единиц на вашем денежном счёте.
        </p>
        <p class="text-justify">
            Мы предоставляем вам
            <span class="strong">максимально возможное число способов для пополнения вашего денежного счёта</span>,
            в каком бы регионе земного шара вы не находились.
            Воспользоваться правом на зачисление
            <span class="strong">1 единицы</span>
            на ваш денежный счёт вы можете примерно за <span class="strong"><?= FormatHelper::asCurrency(1) ?></span>,
            но точная стоимость зависит от способа оплаты. Выбирайте тот способ, который для вас удобнее.
        </p>
        <p class="text-justify">
            Средства на денежном счёте менеджера являются игровым понятием
            и могут быть использованы только для покупки
            <?= Html::a('игровых товаров', ['store/index']) ?> на нашем сайте.
            Средства на денежном счёте менеджера хранятся неограниченное время до удаления аккаунта менеджера.
        </p>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-center">
            <span class="strong">Ваш личный бонус</span>:
            <?= $bonusLine ?>
        </p>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-center">
            <span class="strong">Внимание!</span>
            При пополнении денежного счета одним платежом на большую сумму полагаются бонусы:
            <br/>
            если сумма пополнения счёта <span class="strong">от 100</span> единиц денежного счета,
            то вы получите <span class="strong">+10% от суммы</span> в подарок.
        </p>
    </div>
</div>
<?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'errorOptions' => ['class' => 'col-lg-3 col-md-3 col-sm-3 col-xs-12 xs-text-center notification-error', 'tag' => 'div'],
        'labelOptions' => ['class' => 'strong'],
        'options' => ['class' => 'row margin-top'],
        'template' =>
            '<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 text-right xs-text-center">{label}</div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-left">{input}</div>
            {error}',
    ],
]) ?>
<?= $form
    ->field($model, 'sum')
    ->textInput(['class' => 'form-control form-small', 'type' => 'number'])
    ->label('Сумма пополнения, единиц') ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton('Пополнить', ['class' => 'btn margin']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
