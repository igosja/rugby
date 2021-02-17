<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Logo;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var View $this
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h3 class="page-header"><?= Html::encode($this->title) ?></h3>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'headerOptions' => ['class' => 'col-lg-1'],
                'label' => 'ID',
                'value' => static function (Logo $model) {
                    return $model->id;
                },
            ],
            [
                'label' => Yii::t('backend', 'views.logo.index.th.date'),
                'value' => static function (Logo $model) {
                    return FormatHelper::asDateTime($model->date);
                },
            ],
            [
                'format' => 'raw',
                'label' => Yii::t('backend', 'views.logo.index.th.team'),
                'value' => static function (Logo $model) {
                    return $model->team->getTeamImageLink();
                },
            ],
            [
                'class' => ActionColumn::class,
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'col-lg-1'],
                'template' => '{view}',
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>