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
        <p class="text-center"><?= Yii::t('frontend', 'views.user.deal.transfer.sell') ?>:</p>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.user.deal.th.date'),
                'label' => Yii::t('frontend', 'views.user.deal.th.date'),
                'value' => static function (Transfer $model) {
                    return FormatHelper::asDate($model->date);
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.th.player'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.player'),
                'value' => static function (Transfer $model) {
                    return $model->player->getPlayerLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => Yii::t('frontend', 'views.user.deal.th.federation'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1 hidden-xs'],
                'label' => Yii::t('frontend', 'views.user.deal.th.federation'),
                'value' => static function (Transfer $model) {
                    return Html::a(
                        $model->player->country->getImage(),
                        ['federation/default/news', 'id' => $model->player->country->federation->id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.position'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                'format' => 'raw',
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                'label' => Yii::t('frontend', 'views.th.position'),
                'value' => static function (Transfer $model) {
                    return $model->position();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.age'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'label' => Yii::t('frontend', 'views.th.age'),
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'value' => static function (Transfer $model) {
                    return $model->age;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.user.deal.th.power'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.user.deal.title.power')],
                'headerOptions' => ['title' => Yii::t('frontend', 'views.user.deal.title.power')],
                'label' => Yii::t('frontend', 'views.user.deal.th.power'),
                'value' => static function (Transfer $model) {
                    return $model->power;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.special'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.special')],
                'format' => 'raw',
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.special')],
                'label' => Yii::t('frontend', 'views.th.special'),
                'value' => static function (Transfer $model) {
                    return $model->special();
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.user.deal.th.seller'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.user.deal.th.seller'),
                'value' => static function (Transfer $model) {
                    return $model->teamSeller->getTeamImageLink();
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.user.deal.th.buyer'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.user.deal.th.buyer'),
                'value' => static function (Transfer $model) {
                    return $model->teamBuyer->getTeamImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => Yii::t('frontend', 'views.user.deal.th.price'),
                'label' => Yii::t('frontend', 'views.user.deal.th.price'),
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
        <p class="text-center"><?= Yii::t('frontend', 'views.user.deal.transfer.buy') ?>:</p>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.user.deal.th.date'),
                'label' => Yii::t('frontend', 'views.user.deal.th.date'),
                'value' => static function (Transfer $model) {
                    return FormatHelper::asDate($model->date);
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.th.player'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.player'),
                'value' => static function (Transfer $model) {
                    return $model->player->getPlayerLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => Yii::t('frontend', 'views.user.deal.th.federation'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1 hidden-xs'],
                'label' => Yii::t('frontend', 'views.user.deal.th.federation'),
                'value' => static function (Transfer $model) {
                    return Html::a(
                        $model->player->country->getImage(),
                        ['federation/default/news', 'id' => $model->player->country->federation->id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.position'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                'format' => 'raw',
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                'label' => Yii::t('frontend', 'views.th.position'),
                'value' => static function (Transfer $model) {
                    return $model->position();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.age'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'label' => Yii::t('frontend', 'views.th.age'),
                'value' => static function (Transfer $model) {
                    return $model->age;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.user.deal.th.power'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.user.deal.title.power')],
                'label' => Yii::t('frontend', 'views.user.deal.th.power'),
                'headerOptions' => ['title' => Yii::t('frontend', 'views.user.deal.title.power')],
                'value' => static function (Transfer $model) {
                    return $model->power;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.special'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.special')],
                'format' => 'raw',
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.special')],
                'label' => Yii::t('frontend', 'views.th.special'),
                'value' => static function (Transfer $model) {
                    return $model->special();
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.user.deal.th.seller'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.user.deal.th.seller'),
                'value' => static function (Transfer $model) {
                    return $model->teamSeller->getTeamImageLink();
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.user.deal.th.buyer'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.user.deal.th.buyer'),
                'value' => static function (Transfer $model) {
                    return $model->teamBuyer->getTeamImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => Yii::t('frontend', 'views.user.deal.th.price'),
                'label' => Yii::t('frontend', 'views.user.deal.th.price'),
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
        <p class="text-center"><?= Yii::t('frontend', 'views.user.deal.loan.sell') ?>:</p>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.user.deal.th.date'),
                'label' => Yii::t('frontend', 'views.user.deal.th.date'),
                'value' => static function (Loan $model) {
                    return FormatHelper::asDate($model->date);
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.th.player'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.player'),
                'value' => static function (Loan $model) {
                    return $model->player->getPlayerLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => Yii::t('frontend', 'views.user.deal.th.federation'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.user.deal.th.federation'),
                'headerOptions' => ['class' => 'col-1 hidden-xs'],
                'value' => static function (Loan $model) {
                    return Html::a(
                        $model->player->country->getImage(),
                        ['federation/default/news', 'id' => $model->player->country->federation->id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.position'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.position'),
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                'value' => static function (Loan $model) {
                    return $model->position();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.age'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'label' => Yii::t('frontend', 'views.th.age'),
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'value' => static function (Loan $model) {
                    return $model->age;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.user.deal.th.power'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.user.deal.title.power')],
                'label' => Yii::t('frontend', 'views.user.deal.th.power'),
                'headerOptions' => ['title' => Yii::t('frontend', 'views.user.deal.title.power')],
                'value' => static function (Loan $model) {
                    return $model->power;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.special'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.special')],
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.special'),
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.special')],
                'value' => static function (Loan $model) {
                    return $model->special();
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.user.deal.th.owner'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.user.deal.th.owner'),
                'value' => static function (Loan $model) {
                    return $model->teamSeller->getTeamImageLink();
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.user.deal.th.loaner'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.user.deal.th.loaner'),
                'value' => static function (Loan $model) {
                    return $model->teamBuyer->getTeamImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.user.deal.th.day'),
                'label' => Yii::t('frontend', 'views.user.deal.th.day'),
                'value' => static function (Loan $model) {
                    return $model->day;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => Yii::t('frontend', 'views.user.deal.th.price'),
                'label' => Yii::t('frontend', 'views.user.deal.th.price'),
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
        <p class="text-center"><?= Yii::t('frontend', 'views.user.deal.loan.buy') ?>:</p>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.user.deal.th.date'),
                'label' => Yii::t('frontend', 'views.user.deal.th.date'),
                'value' => static function (Loan $model) {
                    return FormatHelper::asDate($model->date);
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.th.player'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.player'),
                'value' => static function (Loan $model) {
                    return $model->player->getPlayerLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => Yii::t('frontend', 'views.user.deal.th.federation'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.user.deal.th.federation'),
                'headerOptions' => ['class' => 'col-1 hidden-xs'],
                'value' => static function (Loan $model) {
                    return Html::a(
                        $model->player->country->getImage(),
                        ['federation/default/news', 'id' => $model->player->country->federation->id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.position'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.position'),
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                'value' => static function (Loan $model) {
                    return $model->position();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.age'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'label' => Yii::t('frontend', 'views.th.age'),
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'value' => static function (Loan $model) {
                    return $model->age;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.user.deal.th.power'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.user.deal.title.power')],
                'label' => Yii::t('frontend', 'views.user.deal.th.power'),
                'headerOptions' => ['title' => Yii::t('frontend', 'views.user.deal.title.power')],
                'value' => static function (Loan $model) {
                    return $model->power;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.special'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.special')],
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.special'),
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.special')],
                'value' => static function (Loan $model) {
                    return $model->special();
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.user.deal.th.owner'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.user.deal.th.owner'),
                'value' => static function (Loan $model) {
                    return $model->teamSeller->getTeamImageLink();
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.user.deal.th.loaner'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.user.deal.th.loaner'),
                'value' => static function (Loan $model) {
                    return $model->teamBuyer->getTeamImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.user.deal.th.day'),
                'label' => Yii::t('frontend', 'views.user.deal.th.day'),
                'value' => static function (Loan $model) {
                    return $model->day;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => Yii::t('frontend', 'views.user.deal.th.price'),
                'label' => Yii::t('frontend', 'views.user.deal.th.price'),
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
