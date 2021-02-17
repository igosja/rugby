<?php

// TODO refactor

use common\models\db\ForumMessage;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var array $forumThemeArray
 * @var ForumMessage $model
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-2">
                <?= Html::a(
                    Yii::t('frontend', 'modules.forum.views.bread.forum'),
                    ['default/index']
                ) ?>
                /
                <?= Html::a(
                    $model->forumTheme->forumGroup->forumChapter->name,
                    ['chapter/view', 'id' => $model->forumTheme->forumGroup->forumChapter->id]
                ) ?>
                /
                <?= Html::a(
                    $model->forumTheme->forumGroup->name,
                    ['group/view', 'id' => $model->forumTheme->forumGroup->id]
                ) ?>
                /
                <?= Html::a(
                    $model->forumTheme->name,
                    ['theme/view', 'id' => $model->forumTheme->id]
                ) ?>
                /
                <?= Yii::t('frontend', 'modules.forum.views.bread.move') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1><?= Yii::t('frontend', 'modules.forum.views.message.move.h1') ?></h1>
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
                'template' => '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">{input}</div>{error}',
            ],
        ]) ?>
        <?= $form->field($model, 'forum_theme_id')->dropDownList($forumThemeArray)->label(false) ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton(Yii::t('frontend', 'modules.forum.views.message.move.submit'), ['class' => 'btn margin']) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
