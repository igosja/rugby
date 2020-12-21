<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Player;
use common\models\db\Scout;
use common\models\db\Team;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var bool $onBuilding
 * @var Scout[] $scoutArray
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
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                Уровень:
                <span class="strong"><?= $team->baseScout->level ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                Скорость изучения:
                <span class="strong"><?= $team->baseScout->scout_speed_min ?>%</span>
                -
                <span class="strong"><?= $team->baseScout->scout_speed_max ?>%</span>
                за тур
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                Осталось изучений стилей:
                <span class="strong"><?= $team->availableScout() ?></span>
                из
                <span class="strong"><?= $team->baseScout->my_style_count ?></span>
            </div>
        </div>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center <?php if ($onBuilding) : ?>del<?php endif ?>">
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
<?php if ($scoutArray) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            Игроки вашей команды, находящиеся на изучении:
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
            <table class="table table-bordered table-hover">
                <tr>
                    <th>Игрок</th>
                    <th class="col-1 hidden-xs" title="Национальность">Нац</th>
                    <th class="col-10" title="Позиция">Поз</th>
                    <th class="col-5" title="Возраст">В</th>
                    <th class="col-10" title="Номинальная сила">С</th>
                    <th class="col-15 hidden-xs" title="Спецвозможности">Спец</th>
                    <th class="col-10">Изучение</th>
                    <th class="col-10" title="Прогресс изучения">%</th>
                    <th class="col-1"></th>
                </tr>
                <?php foreach ($scoutArray as $item) : ?>
                    <tr>
                        <td>
                            <?= $item->player->getPlayerLink() ?>
                        </td>
                        <td class="hidden-xs text-center">
                            <?= $item->player->country->getImageLink() ?>
                        </td>
                        <td class="text-center"><?= $item->player->position() ?></td>
                        <td class="text-center"><?= $item->player->age ?></td>
                        <td class="text-center"><?= $item->player->power_nominal ?></td>
                        <td class="hidden-xs text-center"><?= $item->player->special() ?></td>
                        <td class="text-center">Стиль</td>
                        <td class="text-center"><?= $item->percent ?>%</td>
                        <td class="text-center">
                            <?= Html::a(
                                '<i class="fa fa-times-circle"></i>',
                                ['cancel', 'id' => $item->id],
                                ['title' => 'Отменить изучение']
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                <tr>
                    <th>Игрок</th>
                    <th class="hidden-xs" title="Национальность">Нац</th>
                    <th title="Позиция">Поз</th>
                    <th title="Возраст">В</th>
                    <th title="Номинальная сила">С</th>
                    <th class="col-15 hidden-xs" title="Спецвозможности">Спец</th>
                    <th class="col-10">Изучение</th>
                    <th class="col-10" title="Прогресс изучения">%</th>
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
                'attribute' => 'position',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Поз',
                'footerOptions' => ['title' => 'Позиция'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-10', 'title' => 'Позиция'],
                'label' => 'Поз',
                'value' => static function (Player $model) {
                    return $model->position();
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
                'attribute' => 'power',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'С',
                'footerOptions' => ['title' => 'Номинальная сила'],
                'headerOptions' => ['class' => 'col-10', 'title' => 'Номинальная сила'],
                'label' => 'С',
                'value' => static function (Player $model) {
                    return $model->power_nominal;
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
                    return $model->special();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Стиль',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-15'],
                'label' => 'Стиль',
                'value' => static function (Player $model) {
                    $result = '';
                    if ($model->countScout() < 2 && $model->date_no_action < time()) {
                        $result .= ' ' . Html::checkbox('style[' . $model->id . ']');
                    }
                    return $result . ' ' . $model->iconStyle();
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
