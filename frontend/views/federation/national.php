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
                'footer' => Yii::t('frontend', 'views.federation.national.th.national'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.federation.national.th.national'),
                'value' => static function (National $model) {
                    return $model->nationalLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.federation.national.th.user'),
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-25'],
                'label' => Yii::t('frontend', 'views.federation.national.th.user'),
                'value' => static function (National $model) {
                    if (!$model->user) {
                        return '-';
                    }
                    return $model->user->iconVip() . ' ' . $model->user->getUserLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => Yii::t('frontend', 'views.federation.national.th.finance'),
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-25'],
                'label' => Yii::t('frontend', 'views.federation.national.th.finance'),
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
