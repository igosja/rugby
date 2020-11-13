<?php

// TODO refactor


/**
 * @var ForumMessage $model
 */

use common\components\helpers\FormatHelper;
use common\models\db\ForumMessage;

?>


<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= $model->user->forumLogo() ?>
            <?= $model->user->getUserLink(['color' => true]) ?>
        </div>
    </div>
    <div class="row text-size-2 hidden-xs">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            Дата регистрации:
            <?= FormatHelper::asDate($model->user->date_register) ?>
        </div>
    </div>
    <div class="row text-size-2 hidden-xs">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            Рейтинг:
            <?= $model->user->rating ?>
        </div>
    </div>
    <div class="row text-size-2 hidden-xs">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            Команды:
            <?php foreach ($model->user->teams as $team): ?>
                <br/>
                <?= $team->getTeamLink() ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
    <div class="row text-size-2 font-grey">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <?= FormatHelper::asDatetime($model->date) ?>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
            <?= $model->links() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <?= nl2br($model->text) ?>
        </div>
    </div>
    <?php if ($model->date_update) : ?>
        <div class="row text-size-2 font-grey">
            <div class="col-lg-12 col-md-12 col-sm-12">
                Отредактировано в
                <?= FormatHelper::asDatetime($model->date_update) ?>
            </div>
        </div>
    <?php endif; ?>
</div>
