<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\Team;
use yii\helpers\Html;

/**
 * @var int $id
 * @var string $price
 * @var Team $team
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//team/_team-top-left', ['team' => $team]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong text-size-1">
                <?= Yii::t('frontend', 'views.stadium.cancel.title') ?>
            </div>
        </div>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Yii::t('frontend', 'views.stadium.cancel.text') ?>
        <?php if ($price > 0) : ?>
            <?= Yii::t('frontend', 'views.stadium.cancel.bonus') ?>
            <span class="strong"><?= FormatHelper::asCurrency($price) ?></span>.
        <?php else : ?>
            <?= Yii::t('frontend', 'views.stadium.cancel.price') ?>
            <span class="strong"><?= FormatHelper::asCurrency(-$price) ?></span>.
        <?php endif ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::a(Yii::t('frontend', 'views.stadium.cancel.link.ok'), ['cancel', 'id' => $id, 'ok' => true], ['class' => 'btn margin']) ?>
        <?= Html::a(Yii::t('frontend', 'views.stadium.cancel.link.increase'), ['increase'], ['class' => 'btn margin']) ?>
    </div>
</div>
