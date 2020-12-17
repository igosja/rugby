<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Loan;
use common\models\db\Transfer;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProviderLoanFrom
 * @var ActiveDataProvider $dataProviderLoanTo
 * @var ActiveDataProvider $dataProviderTransferFrom
 * @var ActiveDataProvider $dataProviderTransferTo
 * @var View $this
 */

print $this->render('//user/_top');

?>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//user/_user-links') ?>
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
                'label' => 'Дата',
                'value' => static function (Transfer $model) {
                    return FormatHelper::asDate($model->date);
                }
            ],
            [
                'footer' => 'Игрок',
                'format' => 'raw',
                'label' => 'Игрок',
                'value' => static function (Transfer $model) {
                    return $model->player->getPlayerLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => 'Нац',
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'label' => 'Нац',
                'headerOptions' => ['class' => 'col-1 hidden-xs'],
                'value' => static function (Transfer $model) {
                    return Html::a(
                        $model->player->country->getImage(),
                        ['federation/news', 'id' => $model->player->country->federation->id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Поз',
                'footerOptions' => ['title' => 'Позиция'],
                'format' => 'raw',
                'label' => 'Поз',
                'headerOptions' => ['title' => 'Позиция'],
                'value' => static function (Transfer $model) {
                    return $model->position();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'В',
                'footerOptions' => ['title' => 'Возраст'],
                'label' => 'В',
                'headerOptions' => ['title' => 'Возраст'],
                'value' => static function (Transfer $model) {
                    return $model->age;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'С',
                'footerOptions' => ['title' => 'Сила'],
                'label' => 'С',
                'headerOptions' => ['title' => 'Сила'],
                'value' => static function (Transfer $model) {
                    return $model->power;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Спец',
                'footerOptions' => ['title' => 'Спецвозможности'],
                'format' => 'raw',
                'label' => 'Спец',
                'headerOptions' => ['title' => 'Спецвозможности'],
                'value' => static function (Transfer $model) {
                    return $model->special();
                }
            ],
            [
                'footer' => 'Продавец',
                'format' => 'raw',
                'label' => 'Продавец',
                'value' => static function (Transfer $model) {
                    return $model->teamSeller->getTeamImageLink();
                }
            ],
            [
                'footer' => 'Покупатель',
                'format' => 'raw',
                'label' => 'Покупатель',
                'value' => static function (Transfer $model) {
                    return $model->teamBuyer->getTeamImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => 'Цена',
                'label' => 'Цена',
                'value' => static function (Transfer $model) {
                    return FormatHelper::asCurrency($model->price_buyer);
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
                'value' => static function (Transfer $model) {
                    return FormatHelper::asDate($model->date);
                }
            ],
            [
                'footer' => 'Игрок',
                'format' => 'raw',
                'label' => 'Игрок',
                'value' => static function (Transfer $model) {
                    return $model->player->getPlayerLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => 'Нац',
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'label' => 'Нац',
                'headerOptions' => ['class' => 'col-1 hidden-xs'],
                'value' => static function (Transfer $model) {
                    return Html::a(
                        $model->player->country->getImage(),
                        ['federation/news', 'id' => $model->player->country->federation->id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Поз',
                'footerOptions' => ['title' => 'Позиция'],
                'format' => 'raw',
                'label' => 'Поз',
                'headerOptions' => ['title' => 'Позиция'],
                'value' => static function (Transfer $model) {
                    return $model->position();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'В',
                'footerOptions' => ['title' => 'Возраст'],
                'label' => 'В',
                'headerOptions' => ['title' => 'Возраст'],
                'value' => static function (Transfer $model) {
                    return $model->age;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'С',
                'footerOptions' => ['title' => 'Сила'],
                'label' => 'С',
                'headerOptions' => ['title' => 'Сила'],
                'value' => static function (Transfer $model) {
                    return $model->power;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Спец',
                'footerOptions' => ['title' => 'Спецвозможности'],
                'format' => 'raw',
                'label' => 'Спец',
                'headerOptions' => ['title' => 'Спецвозможности'],
                'value' => static function (Transfer $model) {
                    return $model->special();
                }
            ],
            [
                'footer' => 'Продавец',
                'format' => 'raw',
                'label' => 'Продавец',
                'value' => static function (Transfer $model) {
                    return $model->teamSeller->getTeamImageLink();
                }
            ],
            [
                'footer' => 'Покупатель',
                'format' => 'raw',
                'label' => 'Покупатель',
                'value' => static function (Transfer $model) {
                    return $model->teamBuyer->getTeamImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => 'Цена',
                'label' => 'Цена',
                'value' => static function (Transfer $model) {
                    return FormatHelper::asCurrency($model->price_buyer);
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
                'label' => 'Дата',
                'value' => static function (Loan $model) {
                    return FormatHelper::asDate($model->date);
                }
            ],
            [
                'footer' => 'Игрок',
                'format' => 'raw',
                'label' => 'Игрок',
                'value' => static function (Loan $model) {
                    return $model->player->getPlayerLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => 'Нац',
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'label' => 'Нац',
                'headerOptions' => ['class' => 'col-1 hidden-xs'],
                'value' => static function (Loan $model) {
                    return Html::a(
                        $model->player->country->getImage(),
                        ['federation/news', 'id' => $model->player->country->federation->id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Поз',
                'footerOptions' => ['title' => 'Позиция'],
                'format' => 'raw',
                'label' => 'Поз',
                'headerOptions' => ['title' => 'Позиция'],
                'value' => static function (Loan $model) {
                    return $model->position();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'В',
                'footerOptions' => ['title' => 'Возраст'],
                'label' => 'В',
                'headerOptions' => ['title' => 'Возраст'],
                'value' => static function (Loan $model) {
                    return $model->age;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'С',
                'footerOptions' => ['title' => 'Сила'],
                'label' => 'С',
                'headerOptions' => ['title' => 'Сила'],
                'value' => static function (Loan $model) {
                    return $model->power;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Спец',
                'footerOptions' => ['title' => 'Спецвозможности'],
                'format' => 'raw',
                'label' => 'Спец',
                'headerOptions' => ['title' => 'Спецвозможности'],
                'value' => static function (Loan $model) {
                    return $model->special();
                }
            ],
            [
                'footer' => 'Владелец',
                'format' => 'raw',
                'label' => 'Владелец',
                'value' => static function (Loan $model) {
                    return $model->teamSeller->getTeamImageLink();
                }
            ],
            [
                'footer' => 'Арендатор',
                'format' => 'raw',
                'label' => 'Арендатор',
                'value' => static function (Loan $model) {
                    return $model->teamBuyer->getTeamImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Срок',
                'label' => 'Срок',
                'value' => static function (Loan $model) {
                    return $model->day;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => 'Цена',
                'label' => 'Цена',
                'value' => static function (Loan $model) {
                    return FormatHelper::asCurrency($model->price_buyer);
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
                'label' => 'Дата',
                'value' => static function (Loan $model) {
                    return FormatHelper::asDate($model->date);
                }
            ],
            [
                'footer' => 'Игрок',
                'format' => 'raw',
                'label' => 'Игрок',
                'value' => static function (Loan $model) {
                    return $model->player->getPlayerLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => 'Нац',
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'label' => 'Нац',
                'headerOptions' => ['class' => 'col-1 hidden-xs'],
                'value' => static function (Loan $model) {
                    return Html::a(
                        $model->player->country->getImage(),
                        ['federation/news', 'id' => $model->player->country->federation->id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Поз',
                'footerOptions' => ['title' => 'Позиция'],
                'format' => 'raw',
                'label' => 'Поз',
                'headerOptions' => ['title' => 'Позиция'],
                'value' => static function (Loan $model) {
                    return $model->position();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'В',
                'footerOptions' => ['title' => 'Возраст'],
                'label' => 'В',
                'headerOptions' => ['title' => 'Возраст'],
                'value' => static function (Loan $model) {
                    return $model->age;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'С',
                'footerOptions' => ['title' => 'Сила'],
                'label' => 'С',
                'headerOptions' => ['title' => 'Сила'],
                'value' => static function (Loan $model) {
                    return $model->power;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Спец',
                'footerOptions' => ['title' => 'Спецвозможности'],
                'format' => 'raw',
                'label' => 'Спец',
                'headerOptions' => ['title' => 'Спецвозможности'],
                'value' => static function (Loan $model) {
                    return $model->special();
                }
            ],
            [
                'footer' => 'Владелец',
                'format' => 'raw',
                'label' => 'Владелец',
                'value' => static function (Loan $model) {
                    return $model->teamSeller->getTeamImageLink();
                }
            ],
            [
                'footer' => 'Арендатор',
                'format' => 'raw',
                'label' => 'Арендатор',
                'value' => static function (Loan $model) {
                    return $model->teamBuyer->getTeamImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Срок',
                'label' => 'Срок',
                'value' => static function (Loan $model) {
                    return $model->day;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => 'Цена',
                'label' => 'Цена',
                'value' => static function (Loan $model) {
                    return FormatHelper::asCurrency($model->price_buyer);
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
<?= $this->render('//site/_show-full-table') ?>
