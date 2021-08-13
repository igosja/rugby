<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\User;
use frontend\controllers\StoreController;
use yii\helpers\Html;

/**
 * @var string $bonusText
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
            <?= Yii::t('frontend', 'views.store.p.1', [
                'value' => Yii::$app->formatter->asDecimal($user->money, 2)
            ]) ?></p>
    </div>
</div>
<div class="row margin-top-small text-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//store/_links') ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p><?= Yii::t('frontend', 'views.store.p.2') ?></p>
    </div>
</div>
<?php if ($bonusText) : ?>
    <div class="row margin">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert info">
            <?= $bonusText ?>
        </div>
    </div>
<?php endif ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-bordered table-hover">
            <tr>
                <td><?= Yii::t('frontend', 'views.store.join', ['value' => 15]) ?></td>
                <td class="text-right">
                    <?= Html::a(
                        Yii::t('frontend', 'views.store.link.buy', [
                            'value' => StoreController::getStorePriceWithDiscount(2),
                        ]),
                        ['store/vip', 'day' => 15]
                    ) ?>
                </td>
            </tr>
            <tr>
                <td><?= Yii::t('frontend', 'views.store.join', ['value' => 30]) ?></td>
                <td class="text-right">
                    <?= Html::a(
                        Yii::t('frontend', 'views.store.link.buy', [
                            'value' => StoreController::getStorePriceWithDiscount(3),
                        ]),
                        ['store/vip', 'day' => 30]
                    ) ?>
                </td>
            </tr>
            <tr>
                <td><?= Yii::t('frontend', 'views.store.join', ['value' => 60]) ?></td>
                <td class="text-right">
                    <?= Html::a(
                        Yii::t('frontend', 'views.store.link.buy', [
                            'value' => StoreController::getStorePriceWithDiscount(5),
                        ]),
                        ['store/vip', 'day' => 60]
                    ) ?>
                </td>
            </tr>
            <tr>
                <td><?= Yii::t('frontend', 'views.store.join', ['value' => 180]) ?></td>
                <td class="text-right">
                    <?= Html::a(
                        Yii::t('frontend', 'views.store.link.buy', [
                            'value' => StoreController::getStorePriceWithDiscount(10),
                        ]),
                        ['store/vip', 'day' => 180]
                    ) ?>
                </td>
            </tr>
            <tr>
                <td><?= Yii::t('frontend', 'views.store.join', ['value' => 365]) ?></td>
                <td class="text-right">
                    <?= Html::a(
                        Yii::t('frontend', 'views.store.link.buy', [
                            'value' => StoreController::getStorePriceWithDiscount(15),
                        ]),
                        ['store/vip', 'day' => 365]
                    ) ?>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p><?= Yii::t('frontend', 'views.store.p.3') ?></p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p>
            <?= Yii::t('frontend', 'views.store.coefficient') ?>:
            <span class="strong"><?= $user->getStoreCoefficient() ?>x</span>
            <?php if ($user->getStoreCoefficientText()): ?>
                (<?= Yii::t('frontend', 'views.store.reason') ?>: <?= $user->getStoreCoefficientText() ?>)
            <?php endif ?>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-bordered table-hover">
            <tr>
                <td>
                    <?= Yii::t('frontend', 'views.store.money', [
                        'value' => FormatHelper::asCurrency(1000000),
                    ]) ?>
                </td>
                <td class="text-right">
                    <?= Html::a(
                        Yii::t('frontend', 'views.store.link.buy', [
                            'value' => 5 * $user->getStoreCoefficient(),
                        ]),
                        ['store/finance']
                    ) ?>
                </td>
            </tr>
        </table>
    </div>
</div>