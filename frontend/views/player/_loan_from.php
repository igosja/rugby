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
            <?= Yii::t('frontend', 'views.player.loan-form.p', [
                'max' => $model->player->loan->day_max,
                'min' => $model->player->loan->day_min,
                'price' => FormatHelper::asCurrency($model->player->loan->price_seller),
            ]) ?>
        </p>
        <?php $form = ActiveForm::begin() ?>
        <?= $form->field($model, 'off')->hiddenInput(['value' => true])->label(false) ?>
        <p class="text-center">
            <?= Html::submitButton(Yii::t('frontend', 'views.player.loan-form.submit'), ['class' => 'btn']) ?>
        </p>
        <?php $form::end() ?>
        <?php if ($model->loanApplicationArray) : ?>
            <p class="text-center"><?= Yii::t('frontend', 'views.player.loan-form.applications') ?></p>
            <table class="table table-bordered table-hover">
                <tr>
                    <th><?= Yii::t('frontend', 'views.player.loan-form.th.team') ?></th>
                    <th class="col-20"><?= Yii::t('frontend', 'views.player.loan-form.th.time') ?></th>
                    <th class="col-15"><?= Yii::t('frontend', 'views.player.loan-form.th.days') ?></th>
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
