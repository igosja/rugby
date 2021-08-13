<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Team;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/**
 * @var ActiveDataProvider $dataProvider
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1><?= Yii::t('frontend', 'views.team.index.h1') ?></h1>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'footer' => Yii::t('frontend', 'views.team.index.th.country'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.team.index.th.country'),
                'value' => static function (Team $model) {
                    return $model->stadium->city->country->getImageTextLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.team.index.th.team'),
                'headerOptions' => ['class' => 'col-25'],
                'label' => Yii::t('frontend', 'views.team.index.th.team'),
                'value' => static function (Team $model) {
                    return $model->player_number;
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
