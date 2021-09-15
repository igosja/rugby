<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Federation;
use common\models\db\Team;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
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
                    return $model->iconFreeTeam() . $model->getTeamCityLink();
                }
            ],
            [
                'attribute' => 'user',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.manager'),
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-40'],
                'label' => Yii::t('frontend', 'views.th.manager'),
                'value' => static function (Team $model) {
                    return $model->user->iconVip() . ' ' . $model->user->getUserLink();
                }
            ],
            [
                'attribute' => 'last_visit',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.federation.team.th.visit'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-20 hidden-xs'],
                'label' => Yii::t('frontend', 'views.federation.team.th.visit'),
                'value' => static function (Team $model) {
                    return $model->user->lastVisit();
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
