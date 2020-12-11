<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Loan;
use common\models\db\Player;
use common\models\db\Transfer;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProviderLoan
 * @var ActiveDataProvider $dataProviderTransfer
 * @var Player $player
 * @var View $this
 */

print $this->render('//player/_player', ['player' => $player]);

?>
    <div class="row margin-top-small">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= $this->render('//player/_links', ['id' => $player->id]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <p class="text-center">Трансферы:</p>
        </div>
    </div>
    <div class="row">
        <?php

        try {
            $columns = [
                [
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'С',
                    'footerOptions' => ['title' => 'Сезон'],
                    'headerOptions' => ['class' => 'col-5', 'title' => 'Сезон'],
                    'label' => 'С',
                    'value' => static function (Transfer $model) {
                        return $model->season_id;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => 'Поз',
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Позиция'],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => 'Позиция'],
                    'label' => 'Поз',
                    'value' => static function (Transfer $model) {
                        return $model->position();
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => 'В',
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Возраст'],
                    'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => 'Возраст'],
                    'label' => 'В',
                    'value' => static function (Transfer $model) {
                        return $model->age;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => 'С',
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Сила'],
                    'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => 'Сила'],
                    'label' => 'С',
                    'value' => static function (Transfer $model) {
                        return $model->power;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => 'Спец',
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Спецвозможности'],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-10 hidden-xs', 'title' => 'Спецвозможности'],
                    'label' => 'Спец',
                    'value' => static function (Transfer $model) {
                        return $model->special();
                    }
                ],
                [
                    'footer' => 'Продавец',
                    'format' => 'raw',
                    'label' => 'Продавец',
                    'value' => static function (Transfer $model) {
                        return $model->teamSeller->getTeamLink();
                    }
                ],
                [
                    'footer' => 'Покупатель',
                    'format' => 'raw',
                    'label' => 'Покупатель',
                    'headerOptions' => ['class' => 'col-25'],
                    'value' => static function (Transfer $model) {
                        return $model->teamBuyer->getTeamLink();
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-right'],
                    'footer' => 'Цена',
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => 'Цена',
                    'value' => static function (Transfer $model) {
                        return Html::a(
                            FormatHelper::asCurrency($model->price_buyer),
                            ['transfer/view', 'id' => $model->id]
                        );
                    }
                ],
            ];
            print GridView::widget([
                'columns' => $columns,
                'dataProvider' => $dataProviderTransfer,
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
            <p class="text-center">Аренды:</p>
        </div>
    </div>
    <div class="row">
        <?php

        try {
            $columns = [
                [
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'С',
                    'footerOptions' => ['title' => 'Сезон'],
                    'headerOptions' => ['class' => 'col-5', 'title' => 'Сезон'],
                    'label' => 'С',
                    'value' => static function (Loan $model) {
                        return $model->season_id;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => 'Поз',
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Позиция'],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => 'Позиция'],
                    'label' => 'Поз',
                    'value' => static function (Loan $model) {
                        return $model->position();
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => 'В',
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Возраст'],
                    'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => 'Возраст'],
                    'label' => 'В',
                    'value' => static function (Loan $model) {
                        return $model->age;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => 'С',
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Сила'],
                    'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => 'Сила'],
                    'label' => 'С',
                    'value' => static function (Loan $model) {
                        return $model->power;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => 'Спец',
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Спецвозможности'],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-10 hidden-xs', 'title' => 'Спецвозможности'],
                    'label' => 'Спец',
                    'value' => static function (Loan $model) {
                        return $model->special();
                    }
                ],
                [
                    'footer' => 'Владелец',
                    'format' => 'raw',
                    'label' => 'Владелец',
                    'value' => static function (Loan $model) {
                        return $model->teamSeller->getTeamLink();
                    }
                ],
                [
                    'footer' => 'Арендатор',
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-25'],
                    'label' => 'Арендатор',
                    'value' => static function (Loan $model) {
                        return $model->teamBuyer->getTeamLink();
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Срок',
                    'headerOptions' => ['class' => 'col-5'],
                    'label' => 'Срок',
                    'value' => static function (Loan $model) {
                        return $model->day;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-right'],
                    'footer' => 'Цена',
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => 'Цена',
                    'value' => static function (Loan $model) {
                        return Html::a(
                            FormatHelper::asCurrency($model->price_buyer),
                            ['loan/view', 'id' => $model->id]
                        );
                    }
                ],
            ];
            print GridView::widget([
                'columns' => $columns,
                'dataProvider' => $dataProviderLoan,
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
            <?= $this->render('//player/_links', ['id' => $player->id]) ?>
        </div>
    </div>
<?= $this->render('//site/_show-full-table') ?>