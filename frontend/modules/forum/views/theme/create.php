<?php

// TODO refactor

use common\models\db\ForumGroup;
use frontend\models\forms\ForumThemeForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var ForumGroup $forumGroup
 * @var ForumThemeForm $model
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-2">
                <?= Html::a(
                    'Форум',
                    ['default/index']
                ) ?>
                /
                <?= Html::a(
                    $forumGroup->forumChapter->name,
                    ['chapter/view', 'id' => $forumGroup->forumChapter->id]
                ) ?>
                /
                <?= Html::a(
                    $forumGroup->name,
                    ['group/view', 'id' => $forumGroup->id]
                ) ?>
                /
                Создание темы
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1>Создание темы</h1>
            </div>
        </div>
        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'errorOptions' => [
                    'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 xs-text-center notification-error',
                    'tag' => 'div'
                ],
                'labelOptions' => ['class' => 'strong'],
                'options' => ['class' => 'row'],
                'template' =>
                    '<div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 text-right">{label}</div>
                    <div class="col-lg-10 col-md-10 col-sm-9 col-xs-8">{input}</div>
                    {error}',
            ],
        ]) ?>
        <?= $form->field($model, 'name')->textInput() ?>
        <?= $form->field($model, 'text', [
            'template' => '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">{input}</div>{error}',
        ])->textarea(['raw' => 5])->label(false) ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton('Создать', ['class' => 'btn margin']) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
