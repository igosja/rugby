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
            <p class="text-center"><?= Yii::t('frontend', 'views.player.deal.transfers') ?>:</p>
        </div>
    </div>
    <div class="row">
        <?php

        try {
            $columns = [
                [
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.th.season'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.title.season')],
                    'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.season')],
                    'label' => Yii::t('frontend', 'views.th.season'),
                    'value' => static function (Transfer $model) {
                        return $model->season_id;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => Yii::t('frontend', 'views.th.position'),
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.position')],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => Yii::t('frontend', 'views.title.position')],
                    'label' => Yii::t('frontend', 'views.th.position'),
                    'value' => static function (Transfer $model) {
                        return $model->position();
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => Yii::t('frontend', 'views.th.age'),
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.age')],
                    'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => Yii::t('frontend', 'views.title.age')],
                    'label' => Yii::t('frontend', 'views.th.age'),
                    'value' => static function (Transfer $model) {
                        return $model->age;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => Yii::t('frontend', 'views.th.power'),
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.power')],
                    'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => Yii::t('frontend', 'views.title.power')],
                    'label' => Yii::t('frontend', 'views.th.power'),
                    'value' => static function (Transfer $model) {
                        return $model->power;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => Yii::t('frontend', 'views.th.special'),
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.special')],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-10 hidden-xs', 'title' => Yii::t('frontend', 'views.title.special')],
                    'label' => Yii::t('frontend', 'views.th.special'),
                    'value' => static function (Transfer $model) {
                        return $model->special();
                    }
                ],
                [
                    'footer' => Yii::t('frontend', 'views.player.deal.th.seller'),
                    'format' => 'raw',
                    'label' => Yii::t('frontend', 'views.player.deal.th.seller'),
                    'value' => static function (Transfer $model) {
                        return $model->teamSeller->getTeamLink();
                    }
                ],
                [
                    'footer' => Yii::t('frontend', 'views.player.deal.th.buyer'),
                    'format' => 'raw',
                    'label' => Yii::t('frontend', 'views.player.deal.th.buyer'),
                    'headerOptions' => ['class' => 'col-25'],
                    'value' => static function (Transfer $model) {
                        return $model->teamBuyer->getTeamLink();
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-right'],
                    'footer' => Yii::t('frontend', 'views.th.price'),
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => Yii::t('frontend', 'views.th.price'),
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
            <p class="text-center"><?= Yii::t('frontend', 'views.player.deal.loans') ?>:</p>
        </div>
    </div>
    <div class="row">
        <?php

        try {
            $columns = [
                [
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.th.season'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.title.season')],
                    'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.season')],
                    'label' => Yii::t('frontend', 'views.th.season'),
                    'value' => static function (Loan $model) {
                        return $model->season_id;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => Yii::t('frontend', 'views.th.position'),
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.position')],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => Yii::t('frontend', 'views.title.position')],
                    'label' => Yii::t('frontend', 'views.th.position'),
                    'value' => static function (Loan $model) {
                        return $model->position();
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => Yii::t('frontend', 'views.th.age'),
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.age')],
                    'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => Yii::t('frontend', 'views.title.age')],
                    'label' => Yii::t('frontend', 'views.th.age'),
                    'value' => static function (Loan $model) {
                        return $model->age;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => Yii::t('frontend', 'views.th.power'),
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.power')],
                    'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => Yii::t('frontend', 'views.title.power')],
                    'label' => Yii::t('frontend', 'views.th.power'),
                    'value' => static function (Loan $model) {
                        return $model->power;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => Yii::t('frontend', 'views.th.special'),
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.special')],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-10 hidden-xs', 'title' => Yii::t('frontend', 'views.title.special')],
                    'label' => Yii::t('frontend', 'views.th.special'),
                    'value' => static function (Loan $model) {
                        return $model->special();
                    }
                ],
                [
                    'footer' => Yii::t('frontend', 'views.player.deal.th.owner'),
                    'format' => 'raw',
                    'label' => Yii::t('frontend', 'views.player.deal.th.owner'),
                    'value' => static function (Loan $model) {
                        return $model->teamSeller->getTeamLink();
                    }
                ],
                [
                    'footer' => Yii::t('frontend', 'views.player.deal.th.renter'),
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-25'],
                    'label' => Yii::t('frontend', 'views.player.deal.th.renter'),
                    'value' => static function (Loan $model) {
                        return $model->teamBuyer->getTeamLink();
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.player.deal.th.day'),
                    'headerOptions' => ['class' => 'col-5'],
                    'label' => Yii::t('frontend', 'views.player.deal.th.day'),
                    'value' => static function (Loan $model) {
                        return $model->day;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-right'],
                    'footer' => Yii::t('frontend', 'views.th.price'),
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => Yii::t('frontend', 'views.th.price'),
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