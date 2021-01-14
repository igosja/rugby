<?php

// TODO refactor

use yii\helpers\Html;

/**
 * @var int $count
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <h1><?= Yii::t('frontend', 'views.vip.h1') ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p class="text-center"><?= Yii::t('frontend', 'views.vip.p.1') ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p><?= Yii::t('frontend', 'views.vip.p.2') ?></p>
                <p><?= Yii::t('frontend', 'views.vip.p.3') ?></p>
                <p><?= Yii::t('frontend', 'views.vip.p.4') ?></p>
                <p><?= Yii::t('frontend', 'views.vip.p.5') ?></p>
                <ul>
                    <li><?= Yii::t('frontend', 'views.vip.li.1') ?></li>
                    <li><?= Yii::t('frontend', 'views.vip.li.2') ?></li>
                    <li><?= Yii::t('frontend', 'views.vip.li.3') ?></li>
                    <li><?= Yii::t('frontend', 'views.vip.li.4') ?></li>
                    <li><?= Yii::t('frontend', 'views.vip.li.5') ?></li>
                    <li><?= Yii::t('frontend', 'views.vip.li.6') ?></li>
                    <li><?= Yii::t('frontend', 'views.vip.li.7') ?></li>
                    <li><?= Yii::t('frontend', 'views.vip.li.8') ?></li>
                    <li><?= Yii::t('frontend', 'views.vip.li.9') ?></li>
                </ul>
                <p><?= Yii::t('frontend', 'views.vip.p.6') ?></p>
                <ul>
                    <li>
                        <?= Yii::t('frontend', 'views.vip.li.10', [
                            'link' => Html::a(Yii::t('frontend', 'views.vip.link.pay'), ['store/payment']),
                        ]) ?>
                    </li>
                    <li>
                        <?= Yii::t('frontend', 'views.vip.li.11', [
                            'link' => Html::a(Yii::t('frontend', 'views.vip.link.store'), ['store/index']),
                        ]) ?>
                    </li>
                </ul>
                <p><?= Yii::t('frontend', 'views.vip.p.7') ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::a(
                    Yii::t('frontend', 'views.vip.link.join'),
                    ['store/index'],
                    ['class' => 'btn margin']
                ) ?>
            </div>
        </div>
    </div>
</div>