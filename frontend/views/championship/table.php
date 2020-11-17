<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Championship;
use common\models\db\Country;
use common\models\db\Federation;
use common\models\db\Game;
use common\models\db\User;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var Federation $federation
 * @var ActiveDataProvider $dataProvider
 * @var array $divisionArray
 * @var int $divisionId
 * @var Game[] $gameArray
 * @var array $scheduleId
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
            <?= Html::a(
                $federation->country->name,
                ['federation/news', 'id' => $federation->country->id],
                ['class' => 'country-header-link']
            ) ?>
        </h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $this->render('//championship/_division-links', ['divisionArray' => $divisionArray]) ?>
    </div>
</div>
<?= Html::beginForm(null, 'get') ?>
<?= Html::hiddenInput('divisionId', $divisionId) ?>
<?= Html::hiddenInput('federationId', $federation->id) ?>
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
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-justify">
            Чемпионаты стран - это основные турниры в Лиге.
            В каждой из стран, где зарегистрированы 16 или более клубов, проводятся национальные чемпионаты.
            Все команды, которые были созданы на момент старта очередных чемпионатов, принимают в них участие.
            Национальные чемпионаты проводятся один раз в сезон.
        </p>
        <p>
            В одном национальном чемпионате может быть несколько дивизионов, в зависимости от числа команд в стране.
            Победители низших дивизионов получают право в следующем сезоне играть в более высоком дивизионе.
            Проигравшие вылетают в более низкий дивизион.
        </p>
    </div>
</div>
<?= Html::beginForm(
    ['', 'federationId' => $federation->id, 'seasonId' => $seasonId, 'divisionId' => $divisionId],
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
                        <?= $item->homeTeam->getTeamLink() ?>
                        <?= $item->formatAuto() ?>
                    </td>
                    <td class="text-center col-10">
                        <?= Html::a(
                            $item->formatScore(),
                            ['game/view', 'id' => $item->id]
                        ) ?>
                    </td>
                    <td>
                        <?= $item->guestTeam->getTeamLink() ?>
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
                'headerOptions' => ['class' => 'col-5', 'title' => 'Место'],
                'label' => 'М',
                'value' => static function (Championship $model) {
                    return $model->place;
                }
            ],
            [
                'footer' => 'Команда',
                'format' => 'raw',
                'label' => 'Команда',
                'value' => static function (Championship $model) {
                    return $model->team->iconFreeTeam() . $model->team->getTeamLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'И',
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Игры'],
                'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => 'Игры'],
                'label' => 'И',
                'value' => static function (Championship $model) {
                    return $model->game;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'B',
                'footerOptions' => ['title' => 'Победы'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Победы'],
                'label' => 'B',
                'value' => static function (Championship $model) {
                    return $model->win;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Н',
                'footerOptions' => ['title' => 'Ничьи'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Ничьи'],
                'label' => 'Н',
                'value' => static function (Championship $model) {
                    return $model->draw;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'П',
                'footerOptions' => ['title' => 'Поражения'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Поражения'],
                'label' => 'П',
                'value' => static function (Championship $model) {
                    return $model->loose;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'Р',
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Разность'],
                'headerOptions' => ['class' => 'hidden-xs col-6', 'title' => 'Разность'],
                'label' => 'Р',
                'value' => static function (Championship $model) {
                    return $model->point_for . '-' . $model->point_against;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Б',
                'footerOptions' => ['title' => 'Бонус'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Бонус'],
                'label' => 'Б',
                'value' => static function (Championship $model) {
                    return $model->bonus_loose + $model->bonus_tries;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'О',
                'footerOptions' => ['title' => 'Очки'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Очки'],
                'label' => 'О',
                'value' => static function (Championship $model) {
                    return $model->point;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Vs',
                'footerOptions' => ['title' => 'Рейтинг силы команды в длительных соревнованиях'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Рейтинг силы команды в длительных соревнованиях'],
                'label' => 'Vs',
                'value' => static function (Championship $model) {
                    return $model->team->power_vs;
                },
                'visible' => $user && $user->isVip(),
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'rowOptions' => static function (Championship $model) {
                $class = '';
                $title = '';
                if ($model->place >= 15) {
                    $class = 'tournament-table-down';
                    $title = 'Зона вылета';
                }
                return ['class' => $class, 'title' => $title];
            },
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
                    'championship/statistics',
                    'federationId' => $federation->id,
                    'divisionId' => $divisionId,
                    'seasonId' => $seasonId,
                ],
                ['class' => 'btn margin']
            ) ?>
        </p>
    </div>
</div>
