<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Federation;
use frontend\models\forms\FederationTransferFinance;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var Federation $federation
 * @var FederationTransferFinance $model
 * @var array $teamArray
 */

print $this->render('/default/_federation', [
    'federation' => $federation,
]);

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table">
            <tr>
                <th><?= Yii::t('frontend', 'views.federation.money-transfer.title') ?></th>
            </tr>
        </table>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-center">
            <?= Yii::t('frontend', 'views.federation.money-transfer.p.1') ?>
        </p>
        <p class="text-center"><?= Yii::t('frontend', 'views.federation.money-transfer.p.2') ?></p>
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
        <?php

        try {
            print $form
                ->field($model, 'teamId')
                ->widget(Select2::class, [
                    'data' => $teamArray,
                    'options' => ['prompt' => Yii::t('frontend', 'views.federation.money-transfer.team.prompt')]
                ]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
        <div class="row">
            <div class="col-lg-5 col-md-4 col-sm-4 col-xs-12 text-right xs-text-center">
                <?= Yii::t('frontend', 'views.federation.money-transfer.available') ?>
            </div>
            <div class="col-lg-3 col-md-5 col-sm-5 col-xs-12">
                <span class="strong"><?= FormatHelper::asCurrency($model->federation->finance) ?></span>
            </div>
        </div>
        <?= $form
            ->field($model, 'sum')
            ->textInput(['class' => 'form-control', 'type' => 'number']) ?>
        <?= $form
            ->field($model, 'comment')
            ->textarea(['class' => 'form-control']) ?>
        <p class="text-center">
            <?= Html::submitButton(Yii::t('frontend', 'views.federation.money-transfer.submit'), ['class' => 'btn margin']) ?>
        </p>
        <?php ActiveForm::end() ?>
    </div>
</div>
