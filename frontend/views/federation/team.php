<?php

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

print $this->render('_federation', [
    'federation' => $federation,
]);

?>
<div class="row margin-top">
    <?php

    try {
        $columns = [
            [
                'attribute' => 'team',
                'footer' => 'Команда',
                'format' => 'raw',
                'label' => 'Команда',
                'value' => function (Team $model) {
                    return $model->iconFreeTeam() . $model->teamLink('string', true);
                }
            ],
            [
                'attribute' => 'manager',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Менеджер',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-40'],
                'label' => 'Менеджер',
                'value' => function (Team $model) {
                    return $model->manager->iconVip() . ' ' . $model->manager->userLink();
                }
            ],
            [
                'attribute' => 'last_visit',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'Последний визит',
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-20 hidden-xs'],
                'label' => 'Последний визит',
                'value' => function (Team $model) {
                    return $model->manager->lastVisit();
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

