<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Federation;
use common\models\db\Team;
use rmrevin\yii\fontawesome\FAR;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var Federation $federation
 * @var View $this
 */

print $this->render('/default/_federation', [
    'federation' => $federation,
]);

?>
<div class="row margin-top">
    <?php

    try {
        $columns = [
            [
                'attribute' => 'team',
                'footer' => Yii::t('frontend', 'views.th.team'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.team'),
                'value' => static function (Team $model) {
                    return $model->getTeamLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.federation.free-team.th.recommendation'),
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-40'],
                'label' => Yii::t('frontend', 'views.federation.free-team.th.recommendation'),
                'value' => static function (Team $model) {
                    return $model->recommendation ? $model->recommendation->user->getUserLink() : '-';
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => '',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1'],
                'label' => '',
                'value' => static function (Team $model) {
                    if ($model->recommendation) {
                        $result = Html::a(
                            FAR::icon(FontAwesome::_TIMES_CIRCLE),
                            ['delete', 'id' => $model->stadium->city->country->federation->id, 'teamId' => $model->id],
                            ['title' => Yii::t('frontend', 'views.federation.free-team.link.delete')]
                        );
                    } else {
                        $result = Html::a(
                            FAR::icon(FontAwesome::_CHECK_CIRCLE),
                            ['create', 'id' => $model->stadium->city->country->federation->id, 'teamId' => $model->id],
                            ['title' => Yii::t('frontend', 'views.federation.free-team.link.create')]
                        );
                    }

                    return $result;
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
<?= $this->render('//site/_show-full-table') ?>
