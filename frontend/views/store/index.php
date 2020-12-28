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
        <h1>Виртуальный магазин</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong">
        <p class="text-center">Ваш счёт - <?= Yii::$app->formatter->asDecimal($user->money, 2) ?></p>
    </div>
</div>
<div class="row margin-top-small text-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//store/_links') ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p>
            Если вам нравится эта игра, если вы хотите пользоваться
            более комфортным интерфейсом без рекламы и с дополнительными страничками,
            выделиться VIP-значком, управлять большим числом команд,
            а может у вас просто есть желание и возможность поддержать нашу дальнейшую работу -
            рекомендуем вам оплатить небольшой взнос и стать VIP-менеджером. Спасибо!
        </p>
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
                <td>
                    Вступить в VIP-клуб на 15 дней
                </td>
                <td class="text-right">
                    <?= Html::a(
                        'Купить за ' . StoreController::getStorePriceWithDiscount(2) . ' ед.',
                        ['store/vip', 'day' => 15]
                    ) ?>
                </td>
            </tr>
            <tr>
                <td>
                    Вступить в VIP-клуб на 30 дней
                </td>
                <td class="text-right">
                    <?= Html::a(
                        'Купить за ' . StoreController::getStorePriceWithDiscount(3) . ' ед.',
                        ['store/vip', 'day' => 30]
                    ) ?>
                </td>
            </tr>
            <tr>
                <td>
                    Вступить в VIP-клуб на 60 дней
                </td>
                <td class="text-right">
                    <?= Html::a(
                        'Купить за ' . StoreController::getStorePriceWithDiscount(5) . ' ед.',
                        ['store/vip', 'day' => 60]
                    ) ?>
                </td>
            </tr>
            <tr>
                <td>
                    Вступить в VIP-клуб на 180 дней
                </td>
                <td class="text-right">
                    <?= Html::a(
                        'Купить за ' . StoreController::getStorePriceWithDiscount(10) . ' ед.',
                        ['store/vip', 'day' => 180]
                    ) ?>
                </td>
            </tr>
            <tr>
                <td>
                    Вступить в VIP-клуб на 365 дней
                </td>
                <td class="text-right">
                    <?= Html::a(
                        'Купить за ' . StoreController::getStorePriceWithDiscount(15) . ' ед.',
                        ['store/vip', 'day' => 365]
                    ) ?>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p>
            Развитие команды не требует обязательного совершения покупок в магазине,
            но если вы не привыкли ждать, хотите "всё и сразу" и имеете возможность "ускорить процесс",
            то добро пожаловать в магазин игровых товаров!
            Цены для разных команд разные - чем сильнее команда, тем сложнее её усилить с помощью магазина:
        </p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p>
            Ваш коэффициент цен:
            <span class="strong"><?= $user->getStoreCoefficient() ?>x</span>
            <?php if ($user->getStoreCoefficientText()): ?>
                (Причина: <?= $user->getStoreCoefficientText() ?>)
            <?php endif ?>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-bordered table-hover">
            <tr>
                <td>
                    <?= FormatHelper::asCurrency(1000000) ?> на счёт команды
                </td>
                <td class="text-right">
                    <?= Html::a(
                        'Купить за ' . StoreController::getStorePriceWithDiscount(5 * $user->getStoreCoefficient()) . ' ед.',
                        ['store/finance']
                    ) ?>
                </td>
            </tr>
        </table>
    </div>
</div>