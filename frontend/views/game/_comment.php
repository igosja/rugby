<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\GameComment;
use common\models\db\User;
use common\models\db\UserRole;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;

/**
 * @var GameComment $model
 * @var User $identity
 */

$identity = Yii::$app->user->identity;

?>
<div class="row border-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-2">
        <?= $model->user->getUserLink(['class' => 'strong']) ?>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= nl2br($model->text) ?>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3 font-grey">
        <?= FormatHelper::asDateTime($model->date) ?>
        <?php if (!Yii::$app->user->isGuest && UserRole::ADMIN === $identity->user_role_id) : ?>
            <?= Html::a(
                FAS::icon(FAS::_TRASH),
                ['delete-comment', 'id' => $model->id, 'gameId' => $model->game_id],
                ['title' => 'Удалить']
            ) ?>
        <?php endif ?>
    </div>
</div>