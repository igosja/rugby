<?php

// TODO refactor

use common\models\db\User;
use frontend\models\forms\OAuthFacebook;
use frontend\models\forms\OAuthGoogle;
use rmrevin\yii\fontawesome\FAB;
use rmrevin\yii\fontawesome\FontAwesome;
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
                <th><?= Yii::t('frontend', 'views.user.social.th') ?></th>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Yii::t('frontend', 'views.user.social.p.1') ?>
    </div>
</div>
<div class="row margin-top-small">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
        <?= FAB::icon(FontAwesome::_FACEBOOK_SQUARE) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <?php if ($model->social_facebook_id) : ?>
            <?= Html::a(
                Yii::t('frontend', 'views.user.social.profile'),
                'https://www.facebook.com/app_scoped_user_id/' . $model->social_facebook_id
            ) ?>
            [<?= Html::a(
                Yii::t('frontend', 'views.user.social.disconnect'),
                ['social/disconnect', 'id' => 'fb']
            ) ?>]
        <?php else: ?>
            <?= Html::a(
                Yii::t('frontend', 'views.user.social.connect'),
                OAuthFacebook::getConnectUrl('connect')
            ) ?>
        <?php endif ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
        <?= FAB::icon(FontAwesome::_GOOGLE_PLUS_SQUARE) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <?php if ($model->social_google_id) : ?>
            <?= Html::a(
                Yii::t('frontend', 'views.user.social.profile'),
                'https://plus.google.com/' . $model->social_google_id
            ) ?>
            [<?= Html::a(
                Yii::t('frontend', 'views.user.social.disconnect'),
                ['social/disconnect', 'id' => 'gl']
            ) ?>]
        <?php else: ?>
            <?= Html::a(
                Yii::t('frontend', 'views.user.social.connect'),
                OAuthGoogle::getConnectUrl('connect')
            ) ?>
        <?php endif ?>
    </div>
</div>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Yii::t('frontend', 'views.user.social.p.2') ?>
    </div>
</div>
