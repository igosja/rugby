<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\OffSeason;
use common\models\db\User;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var array $countryArray
 * @var int $countryId
 * @var ActiveDataProvider $dataProvider
 * @var array $seasonArray
 * @var int $seasonId
 * @var User $user
 */

$user = $this->context->user;

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1>Кубок межсезонья</h1>
    </div>
</div>
<?= Html::beginForm(null, 'get') ?>
<div class="row">
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
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
        <?= Html::label('Страна', 'countryId') ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <?= Html::dropDownList(
            'countryId',
            $countryId,
            $countryArray,
            ['class' => 'form-control submit-on-change', 'id' => 'countryId', 'prompt' => 'Все']
        ) ?>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-4"></div>
</div>
<?= Html::endForm() ?>
<div class="row">
    <?php

// TODO refactor

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'М',
                'footerOptions' => ['title' => 'Место'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Место'],
                'label' => 'М',
                'value' => static function (OffSeason $model) {
                    return $model->off_season_place;
                }
            ],
            [
                'footer' => 'Команда',
                'format' => 'raw',
                'label' => 'Команда',
                'value' => static function (OffSeason $model) {
                    return $model->team->iconFreeTeam() . $model->team->teamLink('img');
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'И',
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Игры'],
                'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => 'Игры'],
                'label' => 'И',
                'value' => static function (OffSeason $model) {
                    return $model->off_season_game;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'B',
                'footerOptions' => ['title' => 'Победы'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Победы'],
                'label' => 'B',
                'value' => static function (OffSeason $model) {
                    return $model->off_season_win;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Н',
                'footerOptions' => ['title' => 'Ничьи'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Ничьи'],
                'label' => 'Н',
                'value' => static function (OffSeason $model) {
                    return $model->off_season_draw;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'П',
                'footerOptions' => ['title' => 'Поражения'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Поражения'],
                'label' => 'П',
                'value' => static function (OffSeason $model) {
                    return $model->off_season_loose;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'Р',
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Разность'],
                'headerOptions' => ['class' => 'hidden-xs col-6', 'title' => 'Разность'],
                'label' => 'Р',
                'value' => static function (OffSeason $model) {
                    return $model->off_season_point_for . '-' . $model->off_season_point_against;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Б',
                'footerOptions' => ['title' => 'Бонус'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Бонус'],
                'label' => 'Б',
                'value' => static function (OffSeason $model) {
                    return $model->off_season_bonus_loose + $model->off_season_bonus_tries;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'О',
                'footerOptions' => ['title' => 'Очки'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Очки'],
                'label' => 'О',
                'value' => static function (OffSeason $model) {
                    return $model->off_season_point;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Vs',
                'footerOptions' => ['title' => 'Рейтинг силы команды в длительных соревнованиях'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Рейтинг силы команды в длительных соревнованиях'],
                'label' => 'Vs',
                'value' => static function (OffSeason $model) {
                    return $model->team->team_power_vs;
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
