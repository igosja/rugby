<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Payment;
use miloschuman\highcharts\Highcharts;
use rmrevin\yii\fontawesome\FAR;
use rmrevin\yii\fontawesome\FAS;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var int $countModeration
 * @var array[] $moderation
 * @var array[] $panels
 * @var array $paymentCategories
 * @var array $paymentData
 * @var ActiveDataProvider $paymentDataProvider
 * @var View $this
 */

?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?= $this->title ?></h1>
    </div>
</div>
<div class="row">
    <?php foreach ($panels as $panel): ?>
        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 panel-<?= $panel['class'] ?>">
            <div class="panel panel-<?= $panel['color'] ?>">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <?php

                            try {
                                print FAS::icon($panel['icon'])->size(FAS::SIZE_5X);
                            } catch (InvalidConfigException $e) {
                                ErrorHelper::log($e);
                            }
                            ?>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge admin-<?= $panel['class'] ?>"></div>
                            <div><?= $panel['text'] ?></div>
                        </div>
                    </div>
                </div>
                <?= Html::a(
                    '<div class="panel-footer">
                        <span class="pull-left">' . Yii::t('backend', 'views.site.index.link.details') . '</span>
                        <span class="pull-right">' . FAR::icon(FontAwesome::_ARROW_CIRCLE_RIGHT) . '</span>
                        <div class="clearfix"></div>
                    </div>',
                    $panel['url']
                ) ?>
            </div>
        </div>
    <?php endforeach ?>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= FAR::icon(FontAwesome::_MONEY_BILL_ALT) ?> <?= Yii::t('backend', 'views.site.index.payment') ?>
            </div>
            <div class="panel-body">
                <?php

                try {
                    print Highcharts::widget(
                        [
                            'options' => [
                                'credits' => ['enabled' => false],
                                'legend' => ['enabled' => false],
                                'series' => [
                                    ['name' => Yii::t('backend', 'views.site.index.payment'), 'data' => $paymentData],
                                ],
                                'title' => ['text' => Yii::t('backend', 'views.site.index.payment')],
                                'xAxis' => [
                                    'categories' => $paymentCategories,
                                    'title' => ['text' => Yii::t('backend', 'views.site.index.month')],
                                ],
                                'yAxis' => [
                                    'title' => ['text' => Yii::t('backend', 'views.site.index.amount')],
                                ],
                            ]
                        ]
                    );
                } catch (Exception $e) {
                    ErrorHelper::log($e);
                }
                ?>
                <div id="chart-payment"></div>
                <?php

                try {
                    $columns = [
                        [
                            'attribute' => 'date',
                            'format' => 'datetime',
                            'enableSorting' => false,
                        ],
                        [
                            'attribute' => 'sum',
                            'format' => 'decimal',
                            'enableSorting' => false,
                        ],
                        [
                            'attribute' => 'user_id',
                            'enableSorting' => false,
                            'value' => static function (Payment $model) {
                                return Html::a(
                                    Html::encode($model->user->login),
                                    ['user/view', 'id' => $model->user_id],
                                    ['target' => '_blank']
                                );
                            }
                        ],
                    ];
                    print GridView::widget(
                        [
                            'columns' => $columns,
                            'dataProvider' => $paymentDataProvider,
                        ]
                    );
                } catch (Exception $e) {
                    ErrorHelper::log($e);
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= FAR::icon(FontAwesome::_CHECK_CIRCLE) ?> <?= Yii::t('backend', 'views.site.index.check') ?>
                (<?= $countModeration ?>)
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?php foreach ($moderation as $item): ?>
                        <?= Html::a(
                            $item['text'] . ' <span class="pull-right text-muted small"><em>'
                            . $item['value']
                            . '</em></span>',
                            ['moderation/chat'],
                            ['class' => 'list-group-item']
                        ) ?>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
</div>
