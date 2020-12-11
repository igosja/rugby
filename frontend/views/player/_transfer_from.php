<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use frontend\models\forms\TransferFrom;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var TransferFrom $model
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <p class="text-center">
            Игрок находится на трансфере.
            <br/>
            Начальная стоимость игрока составляет
            <span class="strong"><?= FormatHelper::asCurrency($model->player->transfer->price_seller) ?></span>.
        </p>
        <?php if ($model->player->transfer->is_to_league): ?>
            <p class="text-center">
                В случае отсутствия спроса игрок будет продан Лиге.
            </p>
        <?php endif ?>
        <?php $form = ActiveForm::begin() ?>
        <?= $form->field($model, 'off')->hiddenInput(['value' => true])->label(false) ?>
        <p class="text-center">
            <?= Html::submitButton('Снять с трансфера', ['class' => 'btn']) ?>
        </p>
        <?php $form::end() ?>
        <?php if ($model->transferApplicationArray) : ?>
            <p class="text-center">Заявки на вашего игрока:</p>
            <table class="table table-bordered table-hover">
                <tr>
                    <th>Команда потенциального покупателя</th>
                    <th class="col-20">Время заявки</th>
                </tr>
                <?php foreach ($model->transferApplicationArray as $item): ?>
                    <tr>
                        <td><?= $item->team->getTeamLink() ?></td>
                        <td class="text-center"><?= FormatHelper::asDatetime($item->date) ?></td>
                    </tr>
                <?php endforeach ?>
            </table>
        <?php endif ?>
    </div>
</div>
