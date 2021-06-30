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
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Yii::t('frontend', 'views.base.cancel.p') ?>
        <?php if ($price > 0) : ?>
            <?= Yii::t('frontend', 'views.base.cancel.return') ?>
            <span class="strong"><?= FormatHelper::asCurrency($price) ?></span>.
        <?php else : ?>
            <?= Yii::t('frontend', 'views.base.cancel.price') ?>
            <span class="strong"><?= FormatHelper::asCurrency(-$price) ?></span>.
        <?php endif ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::a(Yii::t('frontend', 'views.base.cancel.link.cancel'), ['cancel', 'id' => $id, 'ok' => true], ['class' => 'btn margin']) ?>
        <?= Html::a(Yii::t('frontend', 'views.base.cancel.link.view'), ['view', 'id' => $team->id], ['class' => 'btn margin']) ?>
    </div>
</div>
