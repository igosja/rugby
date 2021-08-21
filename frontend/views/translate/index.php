<?php

// TODO refactor

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var \common\models\db\TranslateKey $translateKey
 * @var \common\models\db\TranslateOption $translateOption
 * @var \common\models\db\TranslateVote $translateVote
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <h1><?= Yii::t('frontend', 'views.translate.index.h1') ?></h1>
            </div>
        </div>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <span class="strong"><?= $translateKey->text ?></span>
            </div>
        </div>
        <?php $form = ActiveForm::begin([
            'enableClientValidation' => false,
            'fieldConfig' => [
                'errorOptions' => [
                    'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 notification-error',
                    'tag' => 'div'
                ],
            ],
        ]) ?>
        <?= $form
            ->field($translateVote, 'translate_option_id')
            ->radioList(
                ArrayHelper::map($translateKey->translateOptions, 'id', 'text'),
                [
                    'item' => static function ($index, $label, $name, $checked, $value) {
                        return '<div class="row ' . $index . '"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
                            . Html::radio($name, $checked, ['label' => $label, 'value' => $value])
                            . '</div></div>';
                    }
                ]
            )
            ->label(false) ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton(Yii::t('frontend', 'views.translate.index.submit.vote'), ['class' => 'btn margin']) ?>
            </div>
        </div>
        <?= $form
            ->field($translateOption, 'text')
            ->textarea()
            ->label(Yii::t('frontend', 'views.translate.index.label.text')) ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton(Yii::t('frontend', 'views.translate.index.submit.option'), ['class' => 'btn margin']) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
