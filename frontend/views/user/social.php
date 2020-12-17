<?php

// TODO refactor

use common\models\db\User;
use frontend\models\forms\OAuthFacebook;
use frontend\models\forms\OAuthGoogle;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;

/**
 * @var User $model
 */

print $this->render('//user/_top');

?>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//user/_user-links') ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table">
            <tr>
                <th>Мои профили в соцальных сетях</th>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        На этой странице вы можете
        <span class="strong">соединить ваш аккаунт менеджера с теми социальными сетями</span>,
        которыми вы пользуетесь.
    </div>
</div>
<div class="row margin-top-small">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
        <?= FAS::icon(FAS::_FACEBOOK_SQUARE) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <?php if ($model->social_facebook_id) : ?>
            <?= Html::a(
                'Профиль',
                'https://www.facebook.com/app_scoped_user_id/' . $model->social_facebook_id
            ) ?>
            [<?= Html::a(
                'Отключить',
                ['social/disconnect', 'id' => 'fb']
            ) ?>]
        <?php else: ?>
            <?= Html::a(
                'Подключить',
                OAuthFacebook::getConnectUrl('connect')
            ) ?>
        <?php endif ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
        <?= FAS::icon(FAS::_GOOGLE_PLUS_SQUARE) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <?php if ($model->social_google_id) : ?>
            <?= Html::a(
                'Профиль',
                'https://plus.google.com/' . $model->social_google_id
            ) ?>
            [<?= Html::a(
                'Отключить',
                ['social/disconnect', 'id' => 'gl']
            ) ?>]
        <?php else: ?>
            <?= Html::a(
                'Подключить',
                OAuthGoogle::getConnectUrl('connect')
            ) ?>
        <?php endif ?>
    </div>
</div>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        Это позволит входить в игру нажатием одной кнопки.
    </div>
</div>
