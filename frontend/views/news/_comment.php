<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\NewsComment;
use common\models\db\UserRole;
use frontend\controllers\AbstractController;
use rmrevin\yii\fontawesome\FAB;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\helpers\Html;

/**
 * @var AbstractController $context
 * @var NewsComment $model
 */
$context = $this->context;

?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-2">
    <?= $model->user->getUserLink(['class' => 'strong', 'color' => true]) ?>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?= nl2br(Html::encode($model->text)) ?>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3 font-grey">
    <?= FormatHelper::asDateTime($model->date) ?>
    <?php if ($context->user && UserRole::ADMIN === $context->user->user_role_id): ?>
        <?= Html::a(
            FAB::icon(FontAwesome::_TRASH_ALT),
            ['news/delete-comment', 'id' => $model->id, 'newsId' => $model->news_id],
            ['title' => Yii::t('frontend', 'views.news.comment.link.delete')]
        ) ?>
    <?php endif ?>
</div>
