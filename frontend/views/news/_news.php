<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\News;
use yii\helpers\Html;

/**
 * @var News $model
 */

?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong text-size-1">
    <?= $model->title ?>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3 font-grey">
    <?= FormatHelper::asDateTime($model->date) ?>
    -
    <?= $model->user->getUserLink(['class' => 'strong']) ?>
    -
    <?= Html::a(
        Yii::t('frontend', 'views.news.news.comments', ['count' => count($model->newsComments)]),
        ['view', 'id' => $model->id],
        ['class' => 'strong text-size-3']
    ) ?>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?= $model->text ?>
</div>
