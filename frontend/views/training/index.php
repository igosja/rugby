<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Player;
use common\models\db\Team;
use common\models\db\Training;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var bool $onBuilding
 * @var Team $team
 * @var View $this
 * @var Training[] $trainingArray
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//team/_team-top-left', ['team' => $team]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong text-size-1">
                Тренировочный центр
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                Уровень:
                <span class="strong"><?= $team->baseTraining->level ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                Скорость тренировки:
                <span class="strong"><?= $team->baseTraining->training_speed_min ?>%</span>
                -
                <span class="strong"><?= $team->baseTraining->training_speed_max ?>%</span>
                за тур
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                Осталось тренировок силы:
                <span class="strong"><?= $team->availableTrainingPower() ?></span>
                из
                <span class="strong"><?= $team->baseTraining->power_count ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                Осталось спецвозможностей:
                <span class="strong"><?= $team->availableTrainingSpecial() ?></span>
                из
                <span class="strong"><?= $team->baseTraining->special_count ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                Осталось совмещений:
                <span class="strong"><?= $team->availableTrainingPosition() ?></span>
                из
                <span class="strong"><?= $team->baseTraining->position_count ?></span>
            </div>
        </div>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center <?php if ($onBuilding) : ?>del<?php endif ?>">
        <span class="strong">Стоимость тренировок:</span>
        Балл силы
        <span class="strong">
            <?= FormatHelper::asCurrency($team->baseTraining->power_price) ?>
        </span>
        Спецвозможность
        <span class="strong">
            <?= FormatHelper::asCurrency($team->baseTraining->special_price) ?>
        </span>
        Совмещение
        <span class="strong">
            <?= FormatHelper::asCurrency($team->baseTraining->position_price) ?>
        </span>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        Здесь - <span class="strong">в тренировочном центре</span> -
        вы можете назначить тренировки силы, спецвозможностей или совмещений своим игрокам:
    </div>
</div>
<?php if ($trainingArray) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            Игроки вашей команды, находящиеся на тренировке:
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
            <table class="table table-bordered table-hover">
                <tr>
                    <th>Игрок</th>
                    <th class="col-1 hidden-xs" title="Национальность">Нац</th>
                    <th class="col-5" title="Возраст">В</th>
                    <th class="col-10" title="Номинальная сила">С</th>
                    <th class="col-10" title="Позиция">Поз</th>
                    <th class="col-10" title="Спецвозможности">Спец</th>
                    <th class="col-10" title="Прогресс тренировки">%</th>
                    <th class="col-1"></th>
                </tr>
                <?php foreach ($trainingArray as $item) : ?>
                    <tr>
                        <td>
                            <?= $item->player->getPlayerLink() ?>
                        </td>
                        <td class="hidden-xs text-center">
                            <?= $item->player->country->getImageLink() ?>
                        </td>
                        <td class="text-center"><?= $item->player->age ?></td>
                        <td class="text-center">
                            <?= $item->player->power_nominal ?>
                            <?php if ($item->is_power) : ?>
                                + 1
                            <?php endif ?>
                        </td>
                        <td class="text-center">
                            <?= $item->player->position() ?>
                            <?php if ($item->position_id) : ?>
                                + <?= $item->position->name ?>
                            <?php endif ?>
                        </td>
                        <td class="text-center">
                            <?= $item->player->special() ?>
                            <?php if ($item->special_id) : ?>
                                + <?= $item->special->name ?>
                            <?php endif ?>
                        </td>
                        <td class="text-center">
                            <?= $item->percent ?>%
                        </td>
                        <td class="text-center">
                            <?= Html::a(
                                '<i class="fa fa-times-circle"></i>',
                                ['cancel', 'id' => $item->id],
                                ['title' => 'Отменить тренировку']
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                <tr>
                    <th>Игрок</th>
                    <th class="hidden-xs" title="Национальность">Нац</th>
                    <th title="Возраст">В</th>
                    <th title="Позиция">Поз</th>
                    <th title="Номинальная сила">С</th>
                    <th title="Спецвозможности">Спец</th>
                    <th title="Прогресс тренировки">%</th>
                    <th></th>
                </tr>
            </table>
        </div>
    </div>
<?php endif ?>
<?= Html::beginForm(['index']) ?>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'attribute' => 'squad',
                'footer' => 'Игрок',
                'format' => 'raw',
                'label' => 'Игрок',
                'value' => static function (Player $model) {
                    return $model->getPlayerLink();
                }
            ],
            [
                'attribute' => 'country',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'Нац',
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Национальность'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => 'Национальность'],
                'label' => 'Нац',
                'value' => static function (Player $model) {
                    return $model->country->getImageLink();
                }
            ],
            [
                'attribute' => 'age',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'В',
                'footerOptions' => ['title' => 'Возраст'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Возраст'],
                'label' => 'В',
                'value' => static function (Player $model) {
                    return $model->age;
                }
            ],
            [
                'attribute' => 'power_nominal',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'С',
                'footerOptions' => ['title' => 'Номинальная сила'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-10', 'title' => 'Номинальная сила'],
                'label' => 'С',
                'value' => static function (Player $model) {
                    $result = $model->power_nominal;
                    if ($model->date_no_action < time()) {
                        $result .= ' ' . Html::checkbox('power[' . $model->id . ']');
                    }
                    return $result;
                }
            ],
            [
                'attribute' => 'position',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Поз',
                'footerOptions' => ['title' => 'Позиция'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-15', 'title' => 'Позиция'],
                'label' => 'Поз',
                'value' => static function (Player $model) {
                    $result = '<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">'
                        . $model->position()
                        . '</div><div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">';
                    if ($model->date_no_action < time()) {
                        $result .= ' ' . $model->trainingPositionDropDownList();
                    }
                    $result .= '</div></div>';
                    return $result;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Спец',
                'footerOptions' => ['title' => 'Спецвозможности'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-15', 'title' => 'Спецвозможности'],
                'label' => 'Спец',
                'value' => static function (Player $model) {
                    $result = '<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">'
                        . $model->special()
                        . '</div><div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">';
                    if ($model->date_no_action < time()) {
                        $result .= ' ' . $model->trainingSpecialDropDownList();
                    }
                    $result .= '</div></div>';
                    return $result;
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'rowOptions' => static function (Player $model) {
                if ($model->squad) {
                    return ['style' => ['background-color' => '#' . $model->squad->color]];
                }
                return [];
            },
            'showFooter' => true,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton('Продолжить', ['class' => 'btn margin']) ?>
    </div>
</div>
<?= Html::endForm() ?>
<?= $this->render('//site/_show-full-table') ?>
