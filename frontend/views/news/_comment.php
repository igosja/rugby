<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\NewsComment;
use common\models\db\UserRole;
use frontend\controllers\AbstractController;
use rmrevin\yii\fontawesome\FAS;
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
    <?php

// TODO refactor if ($context->user && UserRole::ADMIN === $context->user->user_role_id): ?>
        <?= Html::a(
            FAS::icon(FAS::_TRASH),
            ['news/delete-comment', 'id' => $model->id, 'newsId' => $model->news_id],
            ['title' => 'Удалить']
        ) ?>
    <?php

// TODO refactor endif ?>
</div>
