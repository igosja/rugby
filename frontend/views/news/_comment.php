<?php

use common\components\helpers\FormatHelper;
use common\models\db\NewsComment;
use common\models\db\User;
use common\models\db\UserRole;
use yii\helpers\Html;

/**
 * @var NewsComment $model
 * @var User $identity
 */

$identity = Yii::$app->user->identity;

?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-2">
    <?= $model->user->userLink(['class' => 'strong', 'color' => true]); ?>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?= nl2br(Html::encode($model->news_comment_text)); ?>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3 font-grey">
    <?= FormatHelper::asDateTime($model->news_comment_date); ?>
    <?php if (!Yii::$app->user->isGuest && UserRole::ADMIN == $identity->user_user_role_id) : ?>
        <?= Html::a(
            '<i class="fa fa-trash-o" aria-hidden="true"></i>',
            ['news/delete-comment', 'id' => $model->news_comment_id, 'newsId' => $model->news_comment_news_id],
            ['title' => 'Удалить']
        ); ?>
    <?php endif; ?>
</div>
