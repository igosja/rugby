<?php

// TODO refactor

use common\models\db\Schedule;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var Schedule $schedule
 * @var View $this
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h3 class="page-header">
            <?= Html::encode($this->title) ?>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <p>
            <?= Yii::t('backend', 'views.schedule.index.today') ?>:
            <?= $schedule->tournamentType->name ?? '' ?>
            ,
            <?= $schedule->stage->name ?? '' ?>
        </p>
    </div>
</div>
<ul class="list-inline preview-links text-center">
    <li>
        <?= Html::a(
            Yii::t('backend', 'views.schedule.index.link.past'),
            ['schedule/index', 'id' => 1],
            ['class' => 'btn btn-default']
        ) ?>
    </li>
    <li>
        <?= Html::a(
            Yii::t('backend', 'views.schedule.index.link.future'),
            ['schedule/index', 'id' => -1],
            ['class' => 'btn btn-default']
        ) ?>
    </li>
</ul>
