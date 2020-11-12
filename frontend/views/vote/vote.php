<?php

// TODO refactor

use common\models\db\Vote;
use common\models\db\VoteUser;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var VoteUser $model
 * @var Vote $vote
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <h1>Опрос</h1>
            </div>
        </div>
        <div class="row margin-top">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <span class="strong"><?= $vote->text ?></span>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                <?= $vote->voteStatus->name ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3">
                Автор:
                <?= $vote->user->getUserLink(['color' => true]) ?>
            </div>
        </div>
        <?php

// TODO refactor $form = ActiveForm::begin([
            'fieldConfig' => [
                'errorOptions' => [
                    'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 notification-error',
                    'tag' => 'div'
                ],
            ],
        ]) ?>
        <?= $form
            ->field($model, 'vote_answer_id')
            ->radioList(
                ArrayHelper::map($vote->voteAnswers, 'id', 'text'),
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
                <?= Html::submitButton('Голосовать', ['class' => 'btn margin']) ?>
            </div>
        </div>
        <?php

// TODO refactor ActiveForm::end() ?>
    </div>
</div>
