<?php

// TODO refactor

use common\models\db\Team;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var array $confirmData
 * @var Team $team
 * @var View $this
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//team/_team-top-left', ['team' => $team]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong text-size-1">
                Спортшкола
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Уровень:
                <span class="strong"><?= $team->baseSchool->level ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Время подготовки игрока:
                <span class="strong"><?= $team->baseSchool->school_speed ?></span> дней
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Осталось юниоров:
                <span class="strong"><?= $team->availableSchool() ?></span>
                из
                <span class="strong"><?= $team->baseSchool->player_count ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Из них со спецвозможностью:
                <span class="strong"><?= $team->availableSchoolWithSpecial() ?></span>
                из
                <span class="strong"><?= $team->baseSchool->with_special ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Из них со стилем:
                <span class="strong"><?= $team->availableSchoolWithStyle() ?></span>
                из
                <span class="strong"><?= $team->baseSchool->with_style ?></span>
            </div>
        </div>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        Здесь - <span class="strong">в спортшколе</span> -
        вы можете подготовить молодых игроков для основной команды:
    </div>
</div>
<?= Html::beginForm(['school/start', 'ok' => 1], 'get') ?>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        Начнется подготовка юниора:
        <ul>
            <li>позиция - <?= $confirmData['position']['name'] ?></li>
            <li>
                спецвозможность -
                <?= $confirmData['special']['with'] ? $confirmData['special']['name'] : 'нет' ?>
            </li>
            <li>стиль - <?= $confirmData['style']['with'] ? $confirmData['style']['name'] : 'неизвестно' ?></li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::hiddenInput('position_id', $confirmData['position']['id']) ?>
        <?= Html::hiddenInput('special_id', $confirmData['special']['with']) ?>
        <?= Html::hiddenInput('style_id', $confirmData['style']['with']) ?>
        <?= Html::submitButton('Начать подготовку', ['class' => 'btn margin']) ?>
        <?= Html::a('Отказаться', ['school/index'], ['class' => 'btn margin']) ?>
    </div>
</div>
<?= Html::endForm() ?>
