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
            <?= Yii::t('frontend', 'views.player.transfer-from.on-transfer') ?>
            <br/>
            <?= Yii::t('frontend', 'views.player.transfer-from.price') ?>
            <span class="strong"><?= FormatHelper::asCurrency($model->player->transfer->price_seller) ?></span>.
        </p>
        <?php if ($model->player->transfer->is_to_league): ?>
            <p class="text-center">
                <?= Yii::t('frontend', 'views.player.transfer-from.is-to-league') ?>
            </p>
        <?php endif ?>
        <?php $form = ActiveForm::begin() ?>
        <?= $form->field($model, 'off')->hiddenInput(['value' => true])->label(false) ?>
        <p class="text-center">
            <?= Html::submitButton(Yii::t('frontend', 'views.player.transfer-from.submit'), ['class' => 'btn']) ?>
        </p>
        <?php $form::end() ?>
        <?php if ($model->transferApplicationArray) : ?>
            <p class="text-center"><?= Yii::t('frontend', 'views.player.transfer-from.applications') ?>:</p>
            <table class="table table-bordered table-hover">
                <tr>
                    <th><?= Yii::t('frontend', 'views.player.transfer-from.th.team') ?></th>
                    <th class="col-20"><?= Yii::t('frontend', 'views.player.transfer-from.th.date') ?></th>
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
