<?php

use common\components\helpers\FormatHelper;
use common\models\db\News;
use yii\helpers\Html;

/**
 * @var News $model
 */

?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong text-size-1">
    <?= $model->news_title; ?>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3 font-grey">
    <?= FormatHelper::asDateTime($model->news_date); ?>
    -
    <?= $model->user->userLink(['class' => 'strong']); ?>
    -
    <?= Html::a(
        'Комментарии: ' . count($model->newsComments),
        ['news/view', 'id' => $model->news_id],
        ['class' => 'strong text-size-3']
    ); ?>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?= $model->news_text; ?>
</div>
