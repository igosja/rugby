<?php

// TODO refactor

use common\models\Support;
use yii\helpers\Html;

/**
 * @var Support $model
 */

?>
<div class="row border-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong">
        <?= $model->user->userLink(); ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        Последний визит: <?= $model->user->lastVisit(); ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        Рейтинг: <span class="strong"><?= $model->user->user_rating; ?></span>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        Команды:
        <?php foreach ($model->user->team as $team) : ?>
            <br/>
            <?= $team->teamLink('img'); ?>
        <?php endforeach; ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php if (!$model->support_read) { ?>
            <?= Html::a(
                'Читать новые',
                ['country/support-president-view', 'id' => $model->support_country_id, 'user_id' => $model->user->user_id],
                ['class' => 'strong']
            ); ?>
            |
        <?php } ?>
        <?= Html::a(
            'Написать',
            ['country/support-president-view', 'id' => $model->support_country_id, 'user_id' => $model->user->user_id]
        ); ?>
    </div>
</div>
