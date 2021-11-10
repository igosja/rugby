<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Support;
use rmrevin\yii\fontawesome\FAR;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
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
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-lg-5'],
                'label' => 'Time',
                'value' => static function (Support $model) {
                    $result = FormatHelper::asDateTime($model->date);
                    if (!$model->read) {
                        $result .= ' ' . FAR::icon(FontAwesome::_CLOCK);
                    }
                    return $result;
                }
            ],
            [
                'format' => 'raw',
                'label' => 'User',
                'value' => static function (Support $model) {
                    if ($model->user_id) {
                        $result = $model->user->getUserLink();
                    } else {
                        $result = $model->presidentUser->getUserLink() . ' (president)';
                    }
                    return $result;
                }
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