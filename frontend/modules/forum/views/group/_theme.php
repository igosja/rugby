<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\ForumTheme;
use common\models\db\User;
use common\models\db\UserRole;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;

/**
 * @var ForumTheme $model
 * @var User $user
 */

?>

<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= Html::a(
                $model->name,
                ['theme/view', 'id' => $model->id]
            ) ?>
            <?php

// TODO refactor if ($user && UserRole::ADMIN === $user->user_role_id) : ?>
                |
                <?= Html::a(
                    FAS::icon(FAS::_TRASH),
                    ['theme/delete', 'id' => $model->id],
                    ['class' => 'font-grey']
                ) ?>
            <?php

// TODO refactor endif; ?>
        </div>
    </div>
    <div class="row text-size-2">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= $model->user->getUserLink() ?>
        </div>
    </div>
    <div class="row text-size-2">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= FormatHelper::asDateTime($model->date) ?>
        </div>
    </div>
</div>
<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center">
    <?= count($model->forumMessages) ?>
</div>
<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center">
    <?= $model->count_view ?>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-size-2">
    <?php

// TODO refactor if (isset($model->forumMessages[0])) : ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= $model->forumMessages[0]->user->getUserLink(['color' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= FormatHelper::asDateTime($model->forumMessages[0]->date) ?>
            </div>
        </div>
    <?php

// TODO refactor endif; ?>
</div>
