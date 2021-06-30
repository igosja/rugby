<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\ElectionNational;
use common\models\db\ElectionNationalApplication;
use common\models\db\ElectionNationalVote;
use common\models\db\Federation;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var ElectionNational $electionNational
 * @var Federation $federation
 * @var ElectionNationalVote $model
 */

print $this->render('//federation/_federation', ['federation' => $federation]);

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <h4><?= Yii::t('frontend', 'views.national-election.poll.h4') ?></h4>
            </div>
        </div>
        <div class="row margin-top">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                <?= $electionNational->electionStatus->name ?>
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
            ->field($model, 'election_national_application_id')
            ->radioList($electionNational->electionNationalApplications, [
                'item' => static function ($index, ElectionNationalApplication $model, $name, $checked) {
                    $result = '<div class="row border-top"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
                        . Html::radio($name, $checked, [
                            'index' => $index,
                            'label' => $model->user_id ? $model->user->getUserLink() : Yii::t('frontend', 'views.national-election.poll.against'),
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
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
                            . Yii::t('frontend', 'views.national-election.poll.register')
                            . ' '
                            . FormatHelper::asDate($model->user->date_register)
                            . '</div></div>
                            <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
                            . Yii::t('frontend', 'views.national-election.poll.rating')
                            . ' '
                            . $model->user->rating
                            . '</div></div>
                            <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
                            . Yii::t('frontend', 'views.national-election.poll.power')
                            . ' '
                            . $model->playerPower()
                            . ' ['
                            . Html::a(
                                Yii::t('frontend', 'views.national-election.poll.link.player'),
                                ['player', 'id' => $model->id],
                                ['target' => '_blank']
                            )
                            . ']
                            </div>
                            </div>
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
                <?= Html::submitButton(Yii::t('frontend', 'views.national-election.poll.submit'), ['class' => 'btn margin']) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
