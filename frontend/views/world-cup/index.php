<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Game;
use common\models\db\TournamentType;
use common\models\db\User;
use common\models\db\WorldCup;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var ActiveDataProvider $dataProvider
 * @var array $divisionArray
 * @var int $divisionId
 * @var Game[] $gameArray
 * @var array $nationalTypeArray
 * @var int $nationalTypeId
 * @var array $seasonArray
 * @var int $seasonId
 * @var array $stageArray
 * @var int $stageId
 * @var User $user
 */

$user = Yii::$app->user->identity;

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1>
            Чемпионат мира среди сборных
        </h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $this->render('//world-cup/_national-type-links', ['nationalTypeArray' => $nationalTypeArray]) ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $this->render('//world-cup/_division-links', ['divisionArray' => $divisionArray]) ?>
    </div>
</div>
<?= Html::beginForm(['world-cup/index'], 'get') ?>
<?= Html::hiddenInput('divisionId', $divisionId) ?>
<?= Html::hiddenInput('nationalTypeId', $nationalTypeId) ?>
<div class="row margin-top-small">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
        <?= Html::label('Сезон', 'seasonId') ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <?= Html::dropDownList(
            'seasonId',
            $seasonId,
            $seasonArray,
            ['class' => 'form-control submit-on-change', 'id' => 'seasonId']
        ) ?>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-4"></div>
</div>
<?= Html::endForm() ?>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center team-logo-div">
        <?= Html::img(
            '/img/tournament_type/' . TournamentType::NATIONAL . '.png?v=' . filemtime(Yii::getAlias('@webroot') . '/img/tournament_type/' . TournamentType::NATIONAL . '.png'),
            [
                'alt' => 'Чемпионат мира среди сборных',
                'class' => 'country-logo',
                'title' => 'Чемпионат мира среди сборных',
            ]
        ) ?>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p class="text-justify">
                    Чемпионат мира - это главный турнир для сборных в Лиге.
                    Чемпионат мира проводится один раз в сезон.
                </p>
                <p>
                    В чемпионате мира может быть несколько дивизионов, в зависимости от числа стран в Лиге.
                    Победители низших дивизионов получают право в следующем сезоне играть в более высоком дивизионе.
                    Проигравшие вылетают в более низкий дивизион.
                </p>
            </div>
        </div>
    </div>
</div>
<?= Html::beginForm(
    ['world-cup/index', 'seasonId' => $seasonId, 'divisionId' => $divisionId, 'nationalTypeId' => $nationalTypeId],
    'get'
) ?>
<div class="row margin-top-small">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
        <?= Html::label('Тур', 'stageId') ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <?= Html::dropDownList(
            'stageId',
            $stageId,
            $stageArray,
            ['class' => 'form-control submit-on-change', 'id' => 'stageId']
        ) ?>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-4"></div>
</div>
<?= Html::endForm() ?>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table">
            <?php foreach ($gameArray as $item) : ?>
                <tr>
                    <td class="text-right col-45">
                        <?= $item->homeNational->nationalLink() ?>
                        <?= $item->formatAuto() ?>
                    </td>
                    <td class="text-center col-10">
                        <?= Html::a(
                            $item->formatScore(),
                            ['game/view', 'id' => $item->id]
                        ) ?>
                    </td>
                    <td>
                        <?= $item->guestNational->nationalLink() ?>
                        <?= $item->formatAuto('guest') ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>
<div class="row margin-top-small">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'М',
                'footerOptions' => ['title' => 'Место'],
                'header' => 'М',
                'headerOptions' => ['class' => 'col-5', 'title' => 'Место'],
                'value' => static function (WorldCup $model) {
                    return $model->place;
                }
            ],
            [
                'footer' => 'Команда',
                'format' => 'raw',
                'header' => 'Команда',
                'value' => static function (WorldCup $model) {
                    return $model->national->nationalLink(true);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'И',
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Игры'],
                'header' => 'И',
                'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => 'Игры'],
                'value' => static function (WorldCup $model) {
                    return $model->game;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'B',
                'footerOptions' => ['title' => 'Победы'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Победы'],
                'label' => 'B',
                'value' => static function (WorldCup $model) {
                    return $model->win;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Н',
                'footerOptions' => ['title' => 'Ничьи'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Ничьи'],
                'label' => 'Н',
                'value' => static function (WorldCup $model) {
                    return $model->draw;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'П',
                'footerOptions' => ['title' => 'Поражения'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Поражения'],
                'label' => 'П',
                'value' => static function (WorldCup $model) {
                    return $model->loose;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'Р',
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Разность'],
                'headerOptions' => ['class' => 'hidden-xs col-6', 'title' => 'Разность'],
                'label' => 'Р',
                'value' => static function (WorldCup $model) {
                    return $model->point_for . '-' . $model->point_against;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Б',
                'footerOptions' => ['title' => 'Бонус'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Бонус'],
                'label' => 'Б',
                'value' => static function (WorldCup $model) {
                    return $model->bonus_loose + $model->bonus_try;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'О',
                'footerOptions' => ['title' => 'Очки'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Очки'],
                'label' => 'О',
                'value' => static function (WorldCup $model) {
                    return $model->point;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Vs',
                'footerOptions' => ['title' => 'Рейтинг силы команды в длительных соревнованиях'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Рейтинг силы команды в длительных соревнованиях'],
                'label' => 'Vs',
                'value' => static function (WorldCup $model) {
                    return $model->national->power_vs;
                },
                'visible' => $user && $user->isVip(),
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'showFooter' => true,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<?= $this->render('//site/_show-full-table') ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <p>
            <?= Html::a(
                'Статистика',
                [
                    'world-cup/statistics',
                    'divisionId' => $divisionId,
                    'seasonId' => $seasonId,
                ],
                ['class' => 'btn margin']
            ) ?>
        </p>
    </div>
</div>
