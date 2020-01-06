<?php

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Team;
use common\models\db\TeamRequest;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var Team $model
 * @var ActiveDataProvider $myDataProvider
 * @var View $this
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h1><?= Yii::t('app', 'frontend.views.team-request.index.h1'); ?></h1>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1'],
                'value' => function (TeamRequest $model) {
                    return Html::a(
                        '<i class="fa fa-times-circle"></i>',
                        ['team-request/delete', 'id' => $model->team_request_id],
                        ['title' => Yii::t('app', 'frontend.views.team-request.index.title-delete')]
                    );
                }
            ],
            [
                'footer' => Yii::t('app', 'frontend.views.team-request.index.label-your-team'),
                'format' => 'raw',
                'label' => Yii::t('app', 'frontend.views.team-request.index.label-your-team'),
                'value' => function (TeamRequest $model) {
                    return $model->team->teamLink('img');
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('app', 'frontend.views.team-request.index.label-your-vs'),
                'label' => Yii::t('app', 'frontend.views.team-request.index.label-your-vs'),
                'value' => function (TeamRequest $model) {
                    return $model->team->team_power_vs;
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $myDataProvider,
            'showFooter' => true,
            'showOnEmpty' => false,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1'],
                'value' => function (Team $model) {
                    return Html::a(
                        '<i class="fa fa-check-circle"></i>',
                        ['team-request/request', 'id' => $model->team_id],
                        ['title' => Yii::t('app', 'frontend.views.team-request.index.title-get')]
                    );
                }
            ],
            [
                'attribute' => 'team',
                'footer' => Yii::t('app', 'frontend.views.team-request.index.footer-team'),
                'format' => 'raw',
                'label' => Yii::t('app', 'frontend.views.team-request.index.label-team'),
                'value' => function (Team $model) {
                    return $model->teamLink('string', true);
                }
            ],
            [
                'attribute' => 'country',
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => Yii::t('app', 'frontend.views.team-request.index.footer-country'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'hidden-xs'],
                'label' => Yii::t('app', 'frontend.views.team-request.index.label-country'),
                'value' => function (Team $model) {
                    return $model->stadium->city->country->countryLink();
                }
            ],
            [
                'attribute' => 'base',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('app', 'frontend.views.team-request.index.footer-base'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'hidden-xs'],
                'label' => Yii::t('app', 'frontend.views.team-request.index.label-base'),
                'value' => function (Team $model) {
                    return $model->baseUsed() . ' ' . Yii::t('app', 'frontend.views.team-request.index.from') . ' ' . $model->base->base_slot_max;
                }
            ],
            [
                'attribute' => 'stadium',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('app', 'frontend.views.team-request.index.footer-stadium'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'hidden-xs'],
                'label' => Yii::t('app', 'frontend.views.team-request.index.label-stadium'),
                'value' => function (Team $model) {
                    return Yii::$app->formatter->asInteger($model->stadium->stadium_capacity);
                }
            ],
            [
                'attribute' => 'finance',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('app', 'frontend.views.team-request.index.footer-finance'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'hidden-xs'],
                'label' => Yii::t('app', 'frontend.views.team-request.index.label-finance'),
                'value' => function (Team $model) {
                    return FormatHelper::asCurrency($model->team_finance);
                }
            ],
            [
                'attribute' => 'vs',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('app', 'frontend.views.team-request.index.footer-vs'),
                'footerOptions' => [
                    'class' => 'hidden-xs',
                    'title' => Yii::t('app', 'frontend.views.team-request.index.footer-title-vs'),
                ],
                'headerOptions' => [
                    'class' => 'hidden-xs',
                    'title' => Yii::t('app', 'frontend.views.team-request.index.header-title-vs'),
                ],
                'label' => Yii::t('app', 'frontend.views.team-request.index.label-vs'),
                'value' => function (Team $model) {
                    return $model->team_power_vs;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('app', 'frontend.views.team-request.index.footer-request') . '',
                'footerOptions' => [
                    'class' => 'hidden-xs',
                    'title' => Yii::t('app', 'frontend.views.team-request.index.footer-title-request'),
                ],
                'headerOptions' => [
                    'class' => 'hidden-xs',
                    'title' => Yii::t('app', 'frontend.views.team-request.index.header-title-request'),
                ],
                'label' => Yii::t('app', 'frontend.views.team-request.index.label-request'),
                'value' => function (Team $model) {
                    return count($model->teamRequests);
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'showFooter' => true,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<?= $this->render('//site/_show-full-table'); ?>
