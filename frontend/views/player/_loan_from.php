<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use frontend\models\forms\LoanFrom;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var LoanFrom $model
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <p class="text-center">
            Игрок находится на рынке аренды.
            <br/>
            Начальная стоимость игрока составляет
            <span class="strong"><?= FormatHelper::asCurrency($model->player->loan->price_seller) ?></span>.
            <br/>
            Срок аренды составляет
            <span class="strong">
                <?= $model->player->loan->day_min ?>-<?= $model->player->loan->day_max ?>
            </span>
            дней.
        </p>
        <?php $form = ActiveForm::begin() ?>
        <?= $form->field($model, 'off')->hiddenInput(['value' => true])->label(false) ?>
        <p class="text-center">
            <?= Html::submitButton('Снять с рынка аренды', ['class' => 'btn']) ?>
        </p>
        <?php $form::end() ?>
        <?php if ($model->loanApplicationArray) : ?>
            <p class="text-center">Заявки на вашего игрока:</p>
            <table class="table table-bordered table-hover">
                <tr>
                    <th>Команда потенциального арендатора</th>
                    <th class="col-20">Время заявки</th>
                    <th class="col-15">Срок аренды</th>
                </tr>
                <?php foreach ($model->loanApplicationArray as $item): ?>
                    <tr>
                        <td><?= $item->team->getTeamLink() ?></td>
                        <td class="text-center"><?= FormatHelper::asDatetime($item->date) ?></td>
                        <td class="text-center"><?= $item->day ?></td>
                    </tr>
                <?php endforeach ?>
            </table>
        <?php endif ?>
    </div>
</div>
