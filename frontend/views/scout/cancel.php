<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\Scout;
use common\models\db\Team;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var int $id
 * @var Team $team
 * @var Scout $scout
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
                Скаут центр
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Уровень:
                <span class="strong"><?= $team->baseScout->level ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Скорость изучения:
                <span class="strong"><?= $team->baseScout->scout_speed_min ?>%</span>
                -
                <span class="strong"><?= $team->baseScout->scout_speed_max ?>%</span>
                за тур
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Осталось изучений стилей:
                <span class="strong"><?= $team->availableScout() ?></span>
                из
                <span class="strong"><?= $team->baseScout->my_style_count ?></span>
            </div>
        </div>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <span class="strong">Стоимость изучения:</span>
        Стиля
        <span class="strong">
            <?= FormatHelper::asCurrency($team->baseScout->my_style_price) ?>
        </span>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        Здесь - <span class="strong">в скаут центре</span> -
        вы можете изучить любимые стили игроков:
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        Будут отменены следующие изучения:
        <ul>
            <li>
                <?= $scout->player->playerName() ?> - любимый стиль
            </li>
        </ul>
        Общая компенсация за отмену тренировок
        <span class="strong"><?= FormatHelper::asCurrency($team->baseScout->my_style_price) ?></span>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::a('Отменить изучение', ['cancel', 'id' => $id, 'ok' => true], ['class' => 'btn margin']) ?>
        <?= Html::a('Отказаться', ['index'], ['class' => 'btn margin']) ?>
    </div>
</div>
