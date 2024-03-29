<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\LoanComment;
use common\models\db\User;
use common\models\db\UserRole;
use rmrevin\yii\fontawesome\FAB;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\helpers\Html;

/**
 * @var LoanComment $model
 * @var User $identity
 */

$identity = Yii::$app->user->identity;

?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-2">
    <?= $model->user->getUserLink(['class' => 'strong', 'color' => true]) ?>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?= nl2br(Html::encode($model->text)) ?>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3 font-grey">
    <?= FormatHelper::asDateTime($model->date) ?>
    <?php if (!Yii::$app->user->isGuest && UserRole::ADMIN === $identity->user_role_id) : ?>
        <?= Html::a(
            FAB::icon(FontAwesome::_TRASH_ALT),
            ['delete-comment', 'id' => $model->id, 'loanId' => $model->loan_id],
            ['title' => Yii::t('frontend', 'views.loan.comment.link.delete')]
        ) ?>
    <?php endif ?>
</div>
