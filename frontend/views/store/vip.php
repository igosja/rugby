<?php

// TODO refactor

use common\models\db\User;
use yii\helpers\Html;

/**
 * @var int $day
 * @var string $message
 * @var User $user
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1><?= Yii::t('frontend', 'views.store.h1') ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong">
        <p class="text-center">
            <?= Yii::t('frontend', 'views.store.p.1', ['value' => Yii::$app->formatter->asDecimal($user->money, 2)]) ?>
        </p>
    </div>
</div>
<div class="row margin-top-small text-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//store/_links') ?>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $message ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::a(Yii::t('frontend', 'views.store.vip.link.buy'), ['vip', 'day' => $day, 'ok' => true], ['class' => 'btn margin']) ?>
        <?= Html::a(Yii::t('frontend', 'views.store.vip.link.cancel'), ['index'], ['class' => 'btn margin']) ?>
    </div>
</div>