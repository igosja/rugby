<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Federation;
use common\models\db\Vote;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var Federation $federation
 * @var Vote $model
 */

print $this->render('/default/_federation', [
    'federation' => $federation,
]);

?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-bordered table-hover">
            <tr>
                <th><?= Yii::t('frontend', 'views.federation.vote-create.title') ?></th>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'errorOptions' => [
                    'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 xs-text-center notification-error',
                    'tag' => 'div'
                ],
                'labelOptions' => ['class' => 'strong'],
                'options' => ['class' => 'row'],
                'template' =>
                    '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">{label}</div>
                   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">{input}</div>
                   {error}',
            ],
        ]) ?>
        <?php

        try {
            print $form
                ->field($model, 'federation_id')
                ->widget(Select2::class, ['data' => [
                    $model->federation_id => Yii::t('frontend', 'views.federation.vote-create.federation'),
                    0 => Yii::t('frontend', 'views.federation.vote-create.league'),
                ]]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
        <?= $form->field($model, 'text')->textarea() ?>
        <?php for ($i = 0; $i < 15; $i++) : ?>
            <?= $form->field($model, 'answers[' . $i . ']')->textarea() ?>
        <?php endfor ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton(Yii::t('frontend', 'views.federation.vote-create.submit'), ['class' => 'btn margin']) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
