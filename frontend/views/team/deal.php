<?php

// TODO refactor

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
        <?= $this->render('//team/_team-top-left', ['team' => $team]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <?= $this->render('//team/_team-top-right', ['team' => $team]) ?>
    </div>
</div>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//team/_team-links', ['id' => $team->id]) ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-center"><?= Yii::t('frontend', 'views.team.deal.transfer.sell') ?>:</p>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.team.deal.th.date'),
                'headerOptions' => ['class' => 'col-10'],
                'label' => Yii::t('frontend', 'views.team.deal.th.date'),
                'value' => static function (Transfer $model) {
                    return FormatHelper::asDate($model->ready);
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.th.player'),
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-20'],
                'label' => Yii::t('frontend', 'views.th.player'),
                'value' => static function (Transfer $model) {
                    return $model->player->getPlayerLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => Yii::t('frontend', 'views.th.national'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.national')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => Yii::t('frontend', 'views.title.national')],
                'label' => Yii::t('frontend', 'views.th.national'),
                'value' => static function (Transfer $model) {
                    return $model->player->country->getImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.position'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.position')],
                'label' => Yii::t('frontend', 'views.th.position'),
                'value' => static function (Transfer $model) {
                    return $model->position();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.age'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.age')],
                'label' => Yii::t('frontend', 'views.th.age'),
                'value' => static function (Transfer $model) {
                    return $model->age;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.power'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.power')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.power')],
                'label' => Yii::t('frontend', 'views.th.power'),
                'value' => static function (Transfer $model) {
                    return $model->power;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.special'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.special')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.title.special')],
                'label' => Yii::t('frontend', 'views.th.special'),
                'value' => static function (Transfer $model) {
                    return $model->special();
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.team.deal.th.buyer'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.team.deal.th.buyer'),
                'value' => static function (Transfer $model) {
                    return $model->teamBuyer->getTeamImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => Yii::t('frontend', 'views.th.price'),
                'headerOptions' => ['class' => 'col-13'],
                'label' => Yii::t('frontend', 'views.th.price'),
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
        <p class="text-center"><?= Yii::t('frontend', 'views.team.deal.transfer.buy') ?>:</p>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.team.deal.th.date'),
                'label' => Yii::t('frontend', 'views.team.deal.th.date'),
                'headerOptions' => ['class' => 'col-10'],
                'value' => static function (Transfer $model) {
                    return FormatHelper::asDate($model->ready);
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.th.player'),
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-20'],
                'label' => Yii::t('frontend', 'views.th.player'),
                'value' => static function (Transfer $model) {
                    return $model->player->getPlayerLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => Yii::t('frontend', 'views.th.national'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.national')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => Yii::t('frontend', 'views.title.national')],
                'label' => Yii::t('frontend', 'views.th.national'),
                'value' => static function (Transfer $model) {
                    return $model->player->country->getImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.position'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.position')],
                'label' => Yii::t('frontend', 'views.th.position'),
                'value' => static function (Transfer $model) {
                    return $model->position();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.age'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.age')],
                'label' => Yii::t('frontend', 'views.th.age'),
                'value' => static function (Transfer $model) {
                    return $model->age;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.power'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.power')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.power')],
                'label' => Yii::t('frontend', 'views.th.power'),
                'value' => static function (Transfer $model) {
                    return $model->power;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.special'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.special')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.title.special')],
                'label' => Yii::t('frontend', 'views.th.special'),
                'value' => static function (Transfer $model) {
                    return $model->special();
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.team.deal.th.seller'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.team.deal.th.seller'),
                'value' => static function (Transfer $model) {
                    return $model->teamSeller->getTeamImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => Yii::t('frontend', 'views.th.price'),
                'headerOptions' => ['class' => 'col-13'],
                'label' => Yii::t('frontend', 'views.th.price'),
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
        <p class="text-center"><?= Yii::t('frontend', 'views.team.deal.loan.sell') ?>:</p>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.team.deal.th.date'),
                'headerOptions' => ['class' => 'col-10'],
                'label' => Yii::t('frontend', 'views.team.deal.th.date'),
                'value' => static function (Loan $model) {
                    return FormatHelper::asDate($model->ready);
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.th.player'),
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-20'],
                'label' => Yii::t('frontend', 'views.th.player'),
                'value' => static function (Loan $model) {
                    return $model->player->getPlayerLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => Yii::t('frontend', 'views.th.national'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.national')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => Yii::t('frontend', 'views.title.national')],
                'label' => Yii::t('frontend', 'views.th.national'),
                'value' => static function (Loan $model) {
                    return $model->player->country->getImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.position'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.position')],
                'label' => Yii::t('frontend', 'views.th.position'),
                'value' => static function (Loan $model) {
                    return $model->position();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.age'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.age')],
                'label' => Yii::t('frontend', 'views.th.age'),
                'value' => static function (Loan $model) {
                    return $model->age;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.power'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.power')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.power')],
                'label' => Yii::t('frontend', 'views.th.power'),
                'value' => static function (Loan $model) {
                    return $model->power;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.special'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.special')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.title.special')],
                'label' => Yii::t('frontend', 'views.th.special'),
                'value' => static function (Loan $model) {
                    return $model->special();
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.team.deal.th.loaner'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.team.deal.th.loaner'),
                'value' => static function (Loan $model) {
                    return $model->teamBuyer->getTeamLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.team.deal.th.day'),
                'headerOptions' => ['class' => 'col-5'],
                'label' => Yii::t('frontend', 'views.team.deal.th.day'),
                'value' => static function (Loan $model) {
                    return $model->day;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => Yii::t('frontend', 'views.th.price'),
                'headerOptions' => ['class' => 'col-10'],
                'label' => Yii::t('frontend', 'views.th.price'),
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
        <p class="text-center"><?= Yii::t('frontend', 'views.team.deal.loan.buy') ?>:</p>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.date'),
                'headerOptions' => ['class' => 'col-10'],
                'label' => Yii::t('frontend', 'views.th.date'),
                'value' => static function (Loan $model) {
                    return FormatHelper::asDate($model->ready);
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.th.player'),
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-20'],
                'label' => Yii::t('frontend', 'views.th.player'),
                'value' => static function (Loan $model) {
                    return $model->player->getPlayerLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => Yii::t('frontend', 'views.th.national'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.national')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => Yii::t('frontend', 'views.title.national')],
                'label' => Yii::t('frontend', 'views.th.national'),
                'value' => static function (Loan $model) {
                    return $model->player->country->getImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.position'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.position')],
                'label' => Yii::t('frontend', 'views.th.position'),
                'value' => static function (Loan $model) {
                    return $model->position();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.age'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.age')],
                'label' => Yii::t('frontend', 'views.th.age'),
                'value' => static function (Loan $model) {
                    return $model->age;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.power'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.power')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.power')],
                'label' => Yii::t('frontend', 'views.th.power'),
                'value' => static function (Loan $model) {
                    return $model->power;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.special'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.special')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.title.special')],
                'label' => Yii::t('frontend', 'views.th.special'),
                'value' => static function (Loan $model) {
                    return $model->special();
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.team.deal.th.owner'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.team.deal.th.owner'),
                'value' => static function (Loan $model) {
                    return $model->teamSeller->getTeamImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.team.deal.th.day'),
                'headerOptions' => ['class' => 'col-5'],
                'label' => Yii::t('frontend', 'views.team.deal.th.day'),
                'value' => static function (Loan $model) {
                    return $model->day;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => Yii::t('frontend', 'views.th.price'),
                'headerOptions' => ['class' => 'col-10'],
                'label' => Yii::t('frontend', 'views.th.price'),
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
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//team/_team-links', ['id' => $team->id]) ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table') ?>
