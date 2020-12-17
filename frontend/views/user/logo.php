<?php

// TODO refactor

use common\models\db\Logo;
use common\models\db\User;
use frontend\models\forms\UserLogo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var Logo[] $logoArray
 * @var UserLogo $model
 * @var User $user
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h1>Загрузить фото</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
        <div class="row">
            <div class="col-lg-5 col-md-4 col-sm-3 hidden-xs"></div>
            <div class="col-lg-6 col-md-7 col-sm-8 col-xs-11">
                <span class="strong">Старое фото</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-md-4 col-sm-3 hidden-xs"></div>
            <div class="col-lg-6 col-md-7 col-sm-8 col-xs-11 team-logo-div">
                <span class="team-logo-link">
                    <?php if (file_exists(Yii::getAlias('@webroot') . '/img/user/125/' . $user->id . '.png')) : ?>
                        <?= Html::img(
                            '/img/user/125/' . $user->id . '.png?v=' . filemtime(Yii::getAlias('@webroot') . '/img/user/125/' . $user->id . '.png'),
                            [
                                'alt' => Html::encode($user->login),
                                'class' => 'team-logo',
                                'title' => Html::encode($user->login),
                            ]
                        ) ?>
                    <?php else : ?>
                        Нет фото
                    <?php endif ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
        <div class="row">
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
            <div class="col-lg-6 col-md-7 col-sm-8 col-xs-11">
                <span class="strong">Новое фото</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
            <div class="col-lg-6 col-md-7 col-sm-8 col-xs-11 team-logo-div">
                <span class="team-logo-link">
                    <?php if (file_exists(Yii::getAlias('@webroot') . '/upload/img/user/125/' . $user->id . '.png')) : ?>
                        <?= Html::img(
                            '/upload/img/user/125/' . $user->id . '.png?v=' . filemtime(Yii::getAlias('@webroot') . '/upload/img/user/125/' . $user->id . '.png'),
                            [
                                'alt' => Html::encode($user->login),
                                'class' => 'team-logo',
                                'title' => Html::encode($user->login),
                            ]
                        ) ?>
                    <?php else : ?>
                        Нет фото
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
        Пользователь
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
        <?= $user->getUserLink() ?>
    </div>
</div>
<?= $form->field($model, 'file')->fileInput(['accept' => '.png']) ?>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs"></div>
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
        <ul>
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
            'Вернуться в профиль',
            ['user/view'],
            ['class' => 'btn margin']
        ) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
<?php if ($logoArray) : ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            Список пользователей, чьи фото находятся в процессе проверки (<?= count($logoArray) ?>):
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs"></div>
        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
            <ul>
                <?php foreach ($logoArray as $logo) : ?>
                    <li>
                        <?= $logo->user->getUserLink() ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
<?php endif ?>
