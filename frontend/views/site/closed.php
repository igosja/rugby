<?php

// TODO refactor

use yii\helpers\Html;

$this->title = 'Forbidden (#403)';

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert error">
                <?= Yii::t('frontend', 'views.site.closed.p') ?>
            </div>
        </div>
    </div>
</div>
