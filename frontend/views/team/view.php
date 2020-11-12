<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Player;
use common\models\db\Team;
use frontend\components\AbstractController;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var AbstractController $controller
 * @var ActiveDataProvider $dataProvider
 * @var array $notificationArray
 * @var Team $team
 * @var View $this
 */

$controller = Yii::$app->controller;

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//team/_team-top-left', ['team' => $team]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <?= $this->render('//team/_team-top-right', ['team' => $team]) ?>
    </div>
</div>
<?php

// TODO refactor if ($notificationArray) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <ul>
                <?php

// TODO refactor foreach ($notificationArray as $item) : ?>
                    <li><?= $item ?></li>
                <?php

// TODO refactor endforeach ?>
            </ul>
        </div>
    </div>
<?php

// TODO refactor endif ?>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//team/_team-links', ['id' => $team->team_id]) ?>
    </div>
</div>
<div class="row">
    <?php

// TODO refactor

    try {
        $columns = [
            [
                'class' => SerialColumn::class,
                'contentOptions' => ['class' => 'text-center'],
                'footer' => '№',
                'header' => '№',
            ],
            [
                'attribute' => 'squad',
                'footer' => 'Игрок',
                'format' => 'raw',
                'label' => 'Игрок',
                'value' => static function (Player $model) {
                    return $model->playerLink()
                        . $model->iconPension()
                        . $model->iconInjury()
                        . $model->iconNational()
                        . $model->iconDeal()
                        . $model->iconTraining()
                        . $model->iconLoan()
                        . $model->iconScout();
                }
            ],
            [
                'attribute' => 'country',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'Нац',
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Национальность'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'hidden-xs col-1', 'title' => 'Национальность'],
                'label' => 'Нац',
                'value' => static function (Player $model) {
                    return $model->country->countryImageLink();
                }
            ],
            [
                'attribute' => 'position',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Поз',
                'footerOptions' => ['title' => 'Позиция'],
                'format' => 'raw',
                'headerOptions' => ['title' => 'Позиция'],
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
                'headerOptions' => ['title' => 'Возраст'],
                'label' => 'В',
                'value' => static function (Player $model) {
                    return $model->player_age;
                }
            ],
            [
                'attribute' => 'power_nominal',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'С',
                'footerOptions' => ['title' => 'Номинальная сила'],
                'format' => 'raw',
                'headerOptions' => ['title' => 'Номинальная сила'],
                'label' => 'С',
                'value' => static function (Player $model) {
                    return $model->powerNominal();
                }
            ],
            [
                'attribute' => 'tire',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'У',
                'footerOptions' => ['title' => 'Усталость'],
                'headerOptions' => ['title' => 'Усталость'],
                'label' => 'У',
                'value' => static function (Player $model) {
                    return $model->playerTire();
                }
            ],
            [
                'attribute' => 'physical',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Ф',
                'footerOptions' => ['title' => 'Форма'],
                'format' => 'raw',
                'headerOptions' => ['title' => 'Форма'],
                'label' => 'Ф',
                'value' => static function (Player $model) {
                    return $model->playerPhysical();
                }
            ],
            [
                'attribute' => 'power_real',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'РС',
                'footerOptions' => ['title' => 'Реальная сила'],
                'headerOptions' => ['title' => 'Реальная сила'],
                'label' => 'РС',
                'value' => static function (Player $model) use ($team) {
                    return $team->myTeam() ? $model->player_power_real : '~' . $model->player_power_nominal;
                }
            ],
            [
                'attribute' => 'special',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'Спец',
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Спецвозможности'],
                'headerOptions' => ['class' => 'hidden-xs', 'title' => 'Спецвозможности'],
                'label' => 'Спец',
                'value' => static function (Player $model) {
                    return $model->special();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'Ст',
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Стиль'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'hidden-xs', 'title' => 'Стиль'],
                'label' => 'Ст',
                'value' => static function (Player $model) {
                    return $model->iconStyle(true);
                }
            ],
            [
                'attribute' => 'game_row',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'ИО',
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Играл/отдыхал подряд'],
                'headerOptions' => ['class' => 'hidden-xs', 'title' => 'Играл/отдыхал подряд'],
                'label' => 'ИО',
                'value' => static function (Player $model) {
                    return $model->playerGameRow();
                }
            ],
            [
                'attribute' => 'game',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'И',
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Игры'],
                'headerOptions' => ['class' => 'hidden-xs', 'title' => 'Игры'],
                'label' => 'И',
                'value' => static function (Player $model) {
                    $result = 0;
                    foreach ($model->statisticPlayer as $statisticPlayer) {
                        $result += $statisticPlayer->statistic_player_game;
                    }
                    return $result;
                }
            ],
            [
                'attribute' => 'player_price',
                'contentOptions' => ['class' => 'hidden-xs text-right'],
                'footer' => 'Цена',
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'hidden-xs'],
                'label' => 'Цена',
                'value' => static function (Player $model) {
                    return FormatHelper::asCurrency($model->player_price);
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'rowOptions' => static function (Player $model) use ($team) {
                $result = [];
                if ($model->squad && $team->myTeam()) {
                    $result['style'] = ['background-color' => '#' . $model->squad->squad_color];
                }
                return $result;
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
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//team/_team-links', ['id' => $team->team_id]) ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table') ?>
<div class="row margin-top">
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 text-size-2">
        <span class="italic">Показатели команды:</span>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                - Рейтинг силы команды (Vs)
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                <?= $team->team_power_vs ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                - Сила 15 лучших (s15)
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                <?= $team->team_power_s_15 ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                - Сила 19 лучших (s19)
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                <?= $team->team_power_s_19 ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                - Сила 24 лучших (s24)
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                <?= $team->team_power_s_24 ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                - Стоимость строений
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                <?= FormatHelper::asCurrency($team->team_price_base) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                - Общая стоимость
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                <?= FormatHelper::asCurrency($team->team_price_total) ?>
            </div>
        </div>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Расскажите друзьям о Лиге:
                <p>
                    <?= Html::a(
                        '<i class="fa fa-facebook-official fa-2x" aria-hidden="true"></i>',
                        'https://www.facebook.com/sharer/sharer.php?u=' . Url::to(['site/index'], true),
                        ['class' => ['no-underline'], 'target' => '_blank']
                    ) ?>
                    <?= Html::a(
                        '<i class="fa fa-twitter fa-2x" aria-hidden="true"></i>',
                        'https://twitter.com/intent/tweet?text=Виртуальная Регбийная Лига - лучший бесплатный регбийный онлайн-менеджер.&url=' . Url::to(['site/index'], true),
                        ['class' => ['no-underline'], 'target' => '_blank']
                    ) ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 hidden-xs"></div>
    <?php

// TODO refactor if ($team->myTeam()) : ?>
        <?= $this->render('_team-bottom-forum', ['team' => $team]) ?>
    <?php

// TODO refactor elseif ($controller->myTeam): ?>
        <?= $this->render('_team-bottom-my-team') ?>
    <?php

// TODO refactor endif ?>
</div>