<?php

// TODO refactor

use common\models\db\Logo;
use common\models\db\Team;
use frontend\models\forms\TeamLogo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var Logo[] $logoArray
 * @var TeamLogo $model
 * @var Team $team
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h1><?= Yii::t('frontend', 'views.team.logo.h1') ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
        <div class="row">
            <div class="col-lg-5 col-md-4 col-sm-3 hidden-xs"></div>
            <div class="col-lg-6 col-md-7 col-sm-8 col-xs-11">
                <span class="strong"><?= Yii::t('frontend', 'views.team.logo.old') ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-md-4 col-sm-3 hidden-xs"></div>
            <div class="col-lg-6 col-md-7 col-sm-8 col-xs-11 team-logo-div">
                <span class="team-logo-link">
                    <?php if (file_exists(Yii::getAlias('@webroot') . '/img/team/125/' . $team->id . '.png')) : ?>
                        <?= Html::img(
                            '/img/team/125/' . $team->id . '.png?v=' . filemtime(Yii::getAlias('@webroot') . '/img/team/125/' . $team->id . '.png'),
                            [
                                'alt' => $team->name,
                                'class' => 'team-logo',
                                'title' => $team->name,
                            ]
                        ) ?>
                    <?php else : ?>
                        <?= Yii::t('frontend', 'views.no-emblem') ?>
                    <?php endif ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
        <div class="row">
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
            <div class="col-lg-6 col-md-7 col-sm-8 col-xs-11">
                <span class="strong"><?= Yii::t('frontend', 'views.team.logo.new') ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
            <div class="col-lg-6 col-md-7 col-sm-8 col-xs-11 team-logo-div">
                <span class="team-logo-link">
                    <?php if (file_exists(Yii::getAlias('@webroot') . '/upload/img/team/125/' . $team->id . '.png')) : ?>
                        <?= Html::img(
                            '/upload/img/team/125/' . $team->id . '.png?v=' . filemtime(Yii::getAlias('@webroot') . '/upload/img/team/125/' . $team->id . '.png'),
                            [
                                'alt' => $team->name,
                                'class' => 'team-logo',
                                'title' => $team->name,
                            ]
                        ) ?>
                    <?php else : ?>
                        <?= Yii::t('frontend', 'views.no-emblem') ?>
                    <?php endif ?>
                </span>
            </div>
        </div>
    </div>
</div>
<?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'errorOptions' => [
            'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center team-logo-file-error notification-error',
            'tag' => 'div'
        ],
        'labelOptions' => ['class' => 'strong'],
        'options' => ['class' => 'row'],
        'template' =>
            '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">{label}</div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">{input}</div>
            {error}',
    ],
]) ?>
<div class="row margin-top">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right strong">
        <?= Yii::t('frontend', 'views.team.logo.team') ?>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
        <?= $team->getTeamImageLink() ?>
    </div>
</div>
<?= $form->field($model, 'file')->fileInput(['accept' => '.png']) ?>
<?= $form->field($model, 'text')->textarea() ?>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs"></div>
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
        <ul>
            <li><?= Yii::t('frontend', 'views.team.logo.li.1') ?></li>
            <li><?= Yii::t('frontend', 'views.team.logo.li.2') ?></li>
            <li><?= Yii::t('frontend', 'views.team.logo.li.3') ?></li>
            <li><?= Yii::t('frontend', 'views.team.logo.li.4') ?></li>
            <li><?= Yii::t('frontend', 'views.team.logo.li.5') ?></li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton(Yii::t('frontend', 'views.team.logo.submit'), ['class' => 'btn margin']) ?>
        <?= Html::a(
            Yii::t('frontend', 'views.team.logo.cancel'),
            ['team/view', 'id' => $team->id],
            ['class' => 'btn margin']
        ) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
<?php if ($logoArray) : ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <?= Yii::t('frontend', 'views.team.logo.checking', ['count' => count($logoArray)]) ?>:
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs"></div>
        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
            <ul>
                <?php foreach ($logoArray as $logo) : ?>
                    <li>
                        <?= $logo->team->getTeamImageLink() ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
