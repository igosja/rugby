<?php

// TODO refactor

use common\models\db\Team;
use yii\helpers\Html;

/**
 * @var int $building
 * @var string $message
 * @var Team $team
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//team/_team-top-left', ['team' => $team, 'teamId' => $team->id]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $message ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::a('Строить', ['build', 'building' => $building, 'ok' => true], ['class' => 'btn margin']) ?>
        <?= Html::a('Отказаться', ['view', 'id' => $team->id], ['class' => 'btn margin']) ?>
    </div>
</div>
