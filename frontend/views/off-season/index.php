<?php

// TODO refactor

use yii\helpers\Html;

/**
 * @var int $count
 * @var array $seasonArray
 * @var int $seasonId
 */

?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1>
            <?= Yii::t('frontend', 'views.off-season.index.h1') ?>
        </h1>
    </div>
</div>
<?= Html::beginForm(null, 'get') ?>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
        <?= Html::label(Yii::t('frontend', 'views.label.season'), 'seasonId') ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <?= Html::dropDownList(
            'seasonId',
            $seasonId,
            $seasonArray,
            ['class' => 'form-control submit-on-change', 'id' => 'seasonId']
        ) ?>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-4"></div>
</div>
<?= Html::endForm() ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.off-season.index.p.1', ['count' => $count]) ?>
        </p>
        <p class="text-center">
            <?= Html::a(Yii::t('frontend', 'views.off-season.link.table'), ['table', 'seasonId' => $seasonId]) ?>
            |
            <?= Html::a(Yii::t('frontend', 'views.off-season.link.statistics'), ['statistics', 'seasonId' => $seasonId]) ?>
        </p>
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.off-season.index.p.2') ?>
        </p>
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.off-season.index.p.3') ?>
        </p>
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.off-season.index.p.4') ?>
        </p>
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.off-season.index.p.5') ?>
        </p>
        <ul class="text-left">
            <li><?= Yii::t('frontend', 'views.off-season.index.li.1') ?></li>
            <li><?= Yii::t('frontend', 'views.off-season.index.li.2') ?></li>
        </ul>
    </div>
</div>
