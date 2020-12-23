<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Federation;
use common\models\db\National;
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
                'footer' => 'Сборная',
                'format' => 'raw',
                'label' => 'Сборная',
                'value' => static function (National $model) {
                    return $model->nationalLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Тренер',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-25'],
                'label' => 'Тренер',
                'value' => static function (National $model) {
                    if (!$model->user) {
                        return '-';
                    }
                    return $model->user->iconVip() . ' ' . $model->user->getUserLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => 'Финансы',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-25'],
                'label' => 'Финансы',
                'value' => static function (National $model) {
                    return FormatHelper::asCurrency($model->finance);
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
