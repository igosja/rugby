<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\TransferComment;
use common\models\db\User;
use common\models\db\UserRole;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;

/**
 * @var TransferComment $model
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
            FAS::icon(FAS::_TRASH),
            ['delete-comment', 'id' => $model->id, 'transferId' => $model->transfer_id],
            ['title' => Yii::t('frontend', 'views.transfer.comment.link.delete')]
        ) ?>
    <?php endif ?>
</div>
