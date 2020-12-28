<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\ElectionPresident;
use common\models\db\ElectionPresidentApplication;
use common\models\db\ElectionPresidentVote;
use common\models\db\Federation;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var ElectionPresident $electionPresident
 * @var Federation $federation
 * @var ElectionPresidentVote $model
 */

print $this->render('//federation/_federation', ['federation' => $federation]);

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <h4>Выборы президента федерации</h4>
            </div>
        </div>
        <div class="row margin-top">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                <?= $electionPresident->electionStatus->name ?>
            </div>
        </div>
        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'errorOptions' => ['class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 notification-error'],
                'labelOptions' => ['class' => 'strong'],
                'options' => ['class' => 'row'],
                'template' =>
                    '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">{label}</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">{input}</div>
                    {error}',
            ],
        ]) ?>
        <?= $form
            ->field($model, 'election_president_application_id')
            ->radioList($electionPresident->electionPresidentApplications, [
                'item' => static function ($index, ElectionPresidentApplication $model, $name, $checked) {
                    $result = '<div class="row border-top"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
                        . Html::radio($name, $checked, [
                            'index' => $index,
                            'label' => $model->user_id ? $model->user->getUserLink() : 'Против всех',
                            'value' => $model->id,
                        ])
                        . '</div></div>';
                    if ($model->user_id) {
                        $result .= '<div class="row">
                                <div class="col-lg-1 col-md-1 col-sm-1 hidden-xs text-center">'
                            . $model->user->smallLogo()
                            . '</div>
                                <div class="col-lg-11 col-md-11 col-sm-11 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    Дата регистрации: '
                            . FormatHelper::asDate($model->user->date_register)
                            . '</div></div>
                                    <div class="row margin-top-small">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
                            . nl2br($model->text)
                            . '</div></div>
                                    </div>
                                </div>';
                    }
                    return $result;
                }
            ])
            ->label(false) ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton('Голосовать', ['class' => 'btn margin']) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
