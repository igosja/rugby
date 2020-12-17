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
        <h1>Загрузить эмблему</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
        <div class="row">
            <div class="col-lg-5 col-md-4 col-sm-3 hidden-xs"></div>
            <div class="col-lg-6 col-md-7 col-sm-8 col-xs-11">
                <span class="strong">Старая эмблема</span>
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
                        Нет эмблемы
                    <?php endif ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
        <div class="row">
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
            <div class="col-lg-6 col-md-7 col-sm-8 col-xs-11">
                <span class="strong">Новая эмблема</span>
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
                        Нет эмблемы
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
        Команда
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
            <li>Эмблемы должны быть "плоскими", а не "с эффектом объёма".</li>
            <li>Фон эмблемы должен быть прозрачный.</li>
            <li>Размер картинки: 100x125 пикселей.</li>
            <li>Формат картинки: png.</li>
            <li>Объем файла: не более 50 килобайт.</li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton('Отправить файл', ['class' => 'btn margin']) ?>
        <?= Html::a(
            'Вернуться в ростер команды',
            ['team/view', 'id' => $team->id],
            ['class' => 'btn margin']
        ) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
<?php if ($logoArray) { ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            Список команд, чьи эмблемы находятся в процессе проверки (<?= count($logoArray) ?>):
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
<?php } ?>
