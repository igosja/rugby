<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use frontend\models\forms\OAuthFacebook;
use frontend\models\forms\OAuthGoogle;
use frontend\models\forms\SignInForm;
use rmrevin\yii\fontawesome\FAB;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var SignInForm $signInForm
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h1><?= Yii::t('frontend', 'views.site.sign-in.h1') ?></h1>
    </div>
</div>
<?php $form = ActiveForm::begin(
    [
        'enableAjaxValidation' => true,
        'fieldConfig' => [
            'errorOptions' => [
                'class' => 'col-lg-4 col-md-3 col-sm-3 col-xs-12 xs-text-center notification-error',
                'tag' => 'div'
            ],
            'labelOptions' => ['class' => 'strong'],
            'options' => ['class' => 'row'],
            'template' => '<div class="col-lg-5 col-md-4 col-sm-4 col-xs-12 text-right xs-text-center">{label}</div>
                        <div class="col-lg-3 col-md-5 col-sm-5 col-xs-12">{input}</div>
                        {error}',
        ],
    ]
) ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $form->field($signInForm, 'login')->textInput(['autoFocus' => true]) ?>
        <?= $form->field($signInForm, 'password')->passwordInput() ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton(Yii::t('frontend', 'views.site.sign-in.submit'), ['class' => 'btn margin']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Yii::t('frontend', 'views.site.sign-in.social') ?>:
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?php

        try {
            print Html::a(
                FAB::icon(FontAwesome::_FACEBOOK_SQUARE)->size(FontAwesome::SIZE_2X),
                OAuthFacebook::getConnectUrl('login'),
                ['title' => 'Facebook']
            );
        } catch (InvalidConfigException $e) {
            ErrorHelper::log($e);
        }
        ?>
        <?php

        try {
            print Html::a(
                FAB::icon(FontAwesome::_GOOGLE_PLUS_SQUARE)->size(FontAwesome::SIZE_2X),
                OAuthGoogle::getConnectUrl('login'),
                ['title' => 'Google+']
            );
        } catch (InvalidConfigException $e) {
            ErrorHelper::log($e);
        }
        ?>
    </div>
</div>
