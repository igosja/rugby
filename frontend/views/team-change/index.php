<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Team;
use common\models\db\TeamRequest;
use rmrevin\yii\fontawesome\FAR;
use rmrevin\yii\fontawesome\FontAwesome;
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
        <h1><?= Yii::t('frontend', 'views.team-change.index.h1') ?></h1>
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
                'value' => static function (TeamRequest $model) {
                    return Html::a(
                        FAR::icon(FontAwesome::_TIMES_CIRCLE),
                        ['delete', 'id' => $model->id],
                        ['title' => Yii::t('frontend', 'views.team-change.index.link.delete')]
                    );
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.team-change.index.th.application'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.team-change.index.th.application'),
                'value' => static function (TeamRequest $model) {
                    return $model->team->getTeamLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.vs'),
                'label' => Yii::t('frontend', 'views.th.vs'),
                'value' => static function (TeamRequest $model) {
                    return $model->team->power_vs;
                }
            ],
        ];
        print GridView::widget(
            [
                'columns' => $columns,
                'dataProvider' => $myDataProvider,
                'showFooter' => true,
                'showOnEmpty' => false,
                'summary' => false,
            ]
        );
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
                'value' => static function (Team $model) {
                    return Html::a(
                        FAR::icon(FontAwesome::_CHECK_CIRCLE),
                        ['confirm', 'id' => $model->id],
                        ['title' => Yii::t('frontend', 'views.team-change.index.link.confirm')]
                    );
                }
            ],
            [
                'attribute' => 'team',
                'footer' => Yii::t('frontend', 'views.th.team'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.team'),
                'value' => static function (Team $model) {
                    return Html::a(
                        $model->name,
                        ['team/view', $model->id]
                    );
                }
            ],
            [
                'attribute' => 'country',
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => Yii::t('frontend', 'views.team-change.index.th.country'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'hidden-xs'],
                'label' => Yii::t('frontend', 'views.team-change.index.th.country'),
                'value' => static function (Team $model) {
                    return $model->stadium->city->country->getImageTextLink();
                }
            ],
            [
                'attribute' => 'base',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.team-change.index.th.base'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'hidden-xs'],
                'label' => Yii::t('frontend', 'views.team-change.index.th.base'),
                'value' => static function (Team $model) {
                    return Yii::t('frontend', 'views.team-change.index.base', [
                        'used' => $model->getNumberOfUseSlot(),
                        'max' => $model->base->slot_max,
                    ]);
                }
            ],
            [
                'attribute' => 'stadium',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.team-change.index.th.stadium'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'hidden-xs'],
                'label' => Yii::t('frontend', 'views.team-change.index.th.stadium'),
                'value' => static function (Team $model) {
                    return Yii::$app->formatter->asInteger($model->stadium->capacity);
                }
            ],
            [
                'attribute' => 'finance',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.team-change.index.th.finance'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'hidden-xs'],
                'label' => Yii::t('frontend', 'views.team-change.index.th.finance'),
                'value' => static function (Team $model) {
                    return FormatHelper::asCurrency($model->finance);
                }
            ],
            [
                'attribute' => 'vs',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.th.vs'),
                'footerOptions' => [
                    'class' => 'hidden-xs',
                    'title' => Yii::t('frontend', 'views.title.vs'),
                ],
                'headerOptions' => [
                    'class' => 'hidden-xs',
                    'title' => Yii::t('frontend', 'views.title.vs'),
                ],
                'label' => Yii::t('frontend', 'views.th.vs'),
                'value' => static function (Team $model) {
                    return Yii::$app->formatter->asInteger($model->power_vs);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.team-change.index.th.request'),
                'footerOptions' => [
                    'class' => 'hidden-xs',
                    'title' => Yii::t('frontend', 'views.team-change.index.title.request'),
                ],
                'headerOptions' => [
                    'class' => 'hidden-xs',
                    'title' => Yii::t('frontend', 'views.team-change.index.title.request'),
                ],
                'label' => Yii::t('frontend', 'views.team-change.index.th.request'),
                'value' => static function (Team $model) {
                    return Yii::$app->formatter->asInteger(count($model->teamRequests));
                }
            ],
        ];
        print GridView::widget(
            [
                'columns' => $columns,
                'dataProvider' => $dataProvider,
                'showFooter' => true,
            ]
        );
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<?= $this->render('//site/_show-full-table') ?>
