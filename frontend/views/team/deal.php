<?php

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Loan;
use common\models\db\Team;
use common\models\db\Transfer;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProviderLoanFrom
 * @var ActiveDataProvider $dataProviderLoanTo
 * @var ActiveDataProvider $dataProviderTransferFrom
 * @var ActiveDataProvider $dataProviderTransferTo
 * @var Team $team
 * @var View $this
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//team/_team-top-left', ['team' => $team]); ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <?= $this->render('//team/_team-top-right', ['team' => $team]); ?>
    </div>
</div>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//team/_team-links', ['id' => $team->team_id]); ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-center">Проданы на трансфере:</p>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Дата',
                'headerOptions' => ['class' => 'col-10'],
                'label' => 'Дата',
                'value' => static function (Transfer $model) {
                    return FormatHelper::asDate($model->transfer_ready);
                }
            ],
            [
                'footer' => 'Игрок',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-20'],
                'label' => 'Игрок',
                'value' => static function (Transfer $model) {
                    return $model->player->playerLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => 'Нац',
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1 hidden-xs'],
                'label' => 'Нац',
                'value' => static function (Transfer $model) {
                    return $model->player->country->countryImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Поз',
                'footerOptions' => ['title' => 'Позиция'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Позиция'],
                'label' => 'Поз',
                'value' => static function (Transfer $model) {
                    return $model->position();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'В',
                'footerOptions' => ['title' => 'Возраст'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Возраст'],
                'label' => 'В',
                'value' => static function (Transfer $model) {
                    return $model->transfer_age;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'С',
                'footerOptions' => ['title' => 'Сила'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Сила'],
                'label' => 'С',
                'value' => static function (Transfer $model) {
                    return $model->transfer_power;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Спец',
                'footerOptions' => ['title' => 'Спецвозможности'],
                'headerOptions' => ['class' => 'col-10', 'title' => 'Спецвозможности'],
                'label' => 'Спец',
                'value' => static function (Transfer $model) {
                    return $model->special();
                }
            ],
            [
                'footer' => 'Покупатель',
                'format' => 'raw',
                'label' => 'Покупатель',
                'value' => static function (Transfer $model) {
                    return $model->buyer->teamLink('img');
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => 'Цена',
                'headerOptions' => ['class' => 'col-13'],
                'label' => 'Цена',
                'value' => static function (Transfer $model) {
                    return FormatHelper::asCurrency($model->transfer_price_buyer);
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProviderTransferFrom,
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
        <p class="text-center">Куплены на трансфере:</p>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Дата',
                'label' => 'Дата',
                'headerOptions' => ['class' => 'col-10'],
                'value' => static function (Transfer $model) {
                    return FormatHelper::asDate($model->transfer_ready);
                }
            ],
            [
                'footer' => 'Игрок',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-20'],
                'label' => 'Игрок',
                'value' => static function (Transfer $model) {
                    return $model->player->playerLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => 'Нац',
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1 hidden-xs'],
                'label' => 'Нац',
                'value' => static function (Transfer $model) {
                    return $model->player->country->countryImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Поз',
                'footerOptions' => ['title' => 'Позиция'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Позиция'],
                'label' => 'Поз',
                'value' => static function (Transfer $model) {
                    return $model->position();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'В',
                'footerOptions' => ['title' => 'Возраст'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Возраст'],
                'label' => 'В',
                'value' => static function (Transfer $model) {
                    return $model->transfer_age;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'С',
                'footerOptions' => ['title' => 'Сила'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Сила'],
                'label' => 'С',
                'value' => static function (Transfer $model) {
                    return $model->transfer_power;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Спец',
                'footerOptions' => ['title' => 'Спецвозможности'],
                'headerOptions' => ['class' => 'col-10', 'title' => 'Спецвозможности'],
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
                    return $model->seller->teamLink('img');
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => 'Цена',
                'headerOptions' => ['class' => 'col-13'],
                'label' => 'Цена',
                'value' => static function (Transfer $model) {
                    return FormatHelper::asCurrency($model->transfer_price_buyer);
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProviderTransferTo,
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
        <p class="text-center">Отданы в аренду:</p>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Дата',
                'headerOptions' => ['class' => 'col-10'],
                'label' => 'Дата',
                'value' => static function (Loan $model) {
                    return FormatHelper::asDate($model->loan_ready);
                }
            ],
            [
                'footer' => 'Игрок',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-20'],
                'label' => 'Игрок',
                'value' => static function (Loan $model) {
                    return $model->player->playerLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => 'Нац',
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1 hidden-xs'],
                'label' => 'Нац',
                'value' => static function (Loan $model) {
                    return $model->player->country->countryImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Поз',
                'footerOptions' => ['title' => 'Позиция'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Позиция'],
                'label' => 'Поз',
                'value' => static function (Loan $model) {
                    return $model->position();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'В',
                'footerOptions' => ['title' => 'Возраст'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Возраст'],
                'label' => 'В',
                'value' => static function (Loan $model) {
                    return $model->loan_age;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'С',
                'footerOptions' => ['title' => 'Сила'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Сила'],
                'label' => 'С',
                'value' => static function (Loan $model) {
                    return $model->loan_power;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Спец',
                'footerOptions' => ['title' => 'Спецвозможности'],
                'headerOptions' => ['class' => 'col-10', 'title' => 'Спецвозможности'],
                'label' => 'Спец',
                'value' => static function (Loan $model) {
                    return $model->special();
                }
            ],
            [
                'footer' => 'Арендатор',
                'format' => 'raw',
                'label' => 'Арендатор',
                'value' => static function (Loan $model) {
                    return $model->buyer->teamLink('img');
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Срок',
                'headerOptions' => ['class' => 'col-5'],
                'label' => 'Срок',
                'value' => static function (Loan $model) {
                    return $model->loan_day;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => 'Цена',
                'headerOptions' => ['class' => 'col-10'],
                'label' => 'Цена',
                'value' => static function (Loan $model) {
                    return FormatHelper::asCurrency($model->loan_price_buyer);
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProviderLoanFrom,
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
        <p class="text-center">Взяты в аренду:</p>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Дата',
                'headerOptions' => ['class' => 'col-10'],
                'label' => 'Дата',
                'value' => static function (Loan $model) {
                    return FormatHelper::asDate($model->loan_ready);
                }
            ],
            [
                'footer' => 'Игрок',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-20'],
                'label' => 'Игрок',
                'value' => static function (Loan $model) {
                    return $model->player->playerLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => 'Нац',
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1 hidden-xs'],
                'label' => 'Нац',
                'value' => static function (Loan $model) {
                    return $model->player->country->countryImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Поз',
                'footerOptions' => ['title' => 'Позиция'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Позиция'],
                'label' => 'Поз',
                'value' => static function (Loan $model) {
                    return $model->position();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'В',
                'footerOptions' => ['title' => 'Возраст'],
                'headerOptions' => ['class' => 'col-5', 'title' => 'Возраст'],
                'label' => 'В',
                'value' => static function (Loan $model) {
                    return $model->loan_age;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'С',
                'footerOptions' => ['title' => 'Сила'],
                'label' => 'С',
                'headerOptions' => ['class' => 'col-5', 'title' => 'Сила'],
                'value' => static function (Loan $model) {
                    return $model->loan_power;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Спец',
                'footerOptions' => ['title' => 'Спецвозможности'],
                'headerOptions' => ['class' => 'col-10', 'title' => 'Спецвозможности'],
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
                    return $model->seller->teamLink('img');
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Срок',
                'headerOptions' => ['class' => 'col-5'],
                'label' => 'Срок',
                'value' => static function (Loan $model) {
                    return $model->loan_day;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => 'Цена',
                'headerOptions' => ['class' => 'col-10'],
                'label' => 'Цена',
                'value' => static function (Loan $model) {
                    return FormatHelper::asCurrency($model->loan_price_buyer);
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProviderLoanTo,
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
        <?= $this->render('//team/_team-links', ['id' => $team->team_id]); ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table'); ?>
