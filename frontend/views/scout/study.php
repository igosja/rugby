<?php

// TODO refactor

use common\components\helpers\FormatHelper;
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
<?= Html::beginForm(['study', 'ok' => true], 'get') ?>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        Будут проведены следующие изучения:
        <ul>
            <?php foreach ($confirmData['style'] as $item) : ?>
                <li><?= $item['name'] ?> - любимый стиль</li>
                <?= Html::hiddenInput('style[' . $item['id'] . ']', 1) ?>
            <?php endforeach ?>
        </ul>
        Общая стоимость изучений <span class="strong"><?= FormatHelper::asCurrency($confirmData['price']) ?></span>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton('Начать изучение', ['class' => 'btn margin']) ?>
        <?= Html::a('Отказаться', ['index'], ['class' => 'btn margin']) ?>
    </div>
</div>
<?= Html::endForm() ?>
