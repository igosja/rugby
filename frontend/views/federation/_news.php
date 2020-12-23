<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\News;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;

/**
 * @var News $model
 */

?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong text-size-1">
    <?= $model->title ?>
    <?php if (!Yii::$app->user->isGuest && $model->user_id === Yii::$app->user->id) : ?>
        <span class="text-size-3 font-grey">
            <?= Html::a(
                FAS::icon(FAS::_PENCIL_ALT),
                ['news-update', 'id' => $model->federation_id, 'newsId' => $model->id],
                ['title' => 'Редактировать']
            ) ?>
            |
            <?= Html::a(
                FAS::icon(FAS::_TRASH),
                ['news-delete', 'id' => $model->federation_id, 'newsId' => $model->id],
                ['title' => 'Удалить']
            ) ?>
        </span>
    <?php endif ?>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3 font-grey">
    <?= FormatHelper::asDatetime($model->date) ?>
    -
    <?= $model->user->getUserLink(['class' => 'strong']) ?>
    -
    <?= Html::a(
        'Комментарии: ' . count($model->newsComments),
        ['news-view', 'id' => $model->federation_id, 'newsId' => $model->id],
        ['class' => 'strong text-size-3']
    ) ?>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?= nl2br($model->text) ?>
</div>
