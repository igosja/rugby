<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\News;
use rmrevin\yii\fontawesome\FAB;
use rmrevin\yii\fontawesome\FAS;
use rmrevin\yii\fontawesome\FontAwesome;
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
                FAS::icon(FontAwesome::_PENCIL_ALT),
                ['news-update', 'id' => $model->federation_id, 'newsId' => $model->id],
                ['title' => Yii::t('frontend', 'views.federation.news.link.edit')]
            ) ?>
            |
            <?= Html::a(
                FAB::icon(FontAwesome::_TRASH_ALT),
                ['news-delete', 'id' => $model->federation_id, 'newsId' => $model->id],
                ['title' => Yii::t('frontend', 'views.federation.news.link.delete')]
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
        Yii::t('frontend', 'views.federation.news.comments') . ' ' . count($model->newsComments),
        ['news-view', 'id' => $model->federation_id, 'newsId' => $model->id],
        ['class' => 'strong text-size-3']
    ) ?>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?= nl2br($model->text) ?>
</div>
