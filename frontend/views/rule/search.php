<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\ListView;

/**
 * @var ActiveDataProvider $dataProvider
 * @var View $this
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1><?= Yii::t('frontend', 'views.rule.search.h1') ?></h1>
            </div>
        </div>
        <?php

        try {
            print ListView::widget([
                'dataProvider' => $dataProvider,
                'emptyText' => Yii::t('frontend', 'views.rule.search.empty', ['q' => Yii::$app->request->get('q')]),
                'emptyTextOptions' => ['class' => 'text-center'],
                'itemOptions' => ['class' => 'row'],
                'itemView' => '_search',
            ]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
    </div>
</div>
