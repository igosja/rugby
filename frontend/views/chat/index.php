<?php

/**
 * @var Chat $model
 * @var View $this
 * @var User $user
 * @var UserBlock $userBlockChat
 * @var UserBlock $userBlockComment
 */

use common\components\helpers\FormatHelper;
use common\models\db\Chat;
use common\models\db\User;
use common\models\db\UserBlock;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1><?= $this->title ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
            <div class="chat-scroll" data-url="<?= Url::to(['message']) ?>" data-date="0"></div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <div class="chat-user-scroll" data-url="<?= Url::to(['user']) ?>"></div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php if (!$user->date_confirm) : ?>
                <div class="row margin-top">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                        Вам заблокирован доступ к чату
                        <br/>
                        Причина - ваш почтовый адрес не подтверждён
                    </div>
                </div>
            <?php elseif ($userBlockChat && $userBlockChat->date >= time()) : ?>
                <div class="row margin-top">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                        Вам заблокирован доступ к чату до
                        <?= FormatHelper::asDateTime($userBlockChat->date) ?>
                        <br/>
                        Причина - <?= $userBlockChat->userBlockReason->text ?>
                    </div>
                </div>
            <?php elseif ($userBlockComment && $userBlockComment->date >= time()) : ?>
                <div class="row margin-top">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                        Вам заблокирован доступ к чату до
                        <?= FormatHelper::asDateTime($userBlockComment->date) ?>
                        <br/>
                        Причина - <?= $userBlockComment->userBlockReason->text ?>
                    </div>
                </div>
            <?php else: ?>
                <?php $form = ActiveForm::begin([
                    'action' => ['add'],
                    'id' => 'chat-form',
                    'fieldConfig' => [
                        'errorOptions' => [
                            'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center notification-error',
                            'tag' => 'div'
                        ],
                        'options' => ['class' => 'row'],
                        'template' =>
                            '<div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">{label}</div>
                </div>
                <div class="row margin-top">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">{input}</div>
                </div>
                <div class="row">{error}</div>',
                    ],
                ]) ?>
                <?= $form->field($model, 'message')->textarea()->label(false) ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <?= Html::submitButton('Отправить', ['class' => 'btn margin']) ?>
                    </div>
                </div>
                <?php ActiveForm::end() ?>
            <?php endif ?>
        </div>
    </div>
<?php
$script = <<< JS
$(document).ready(function() {
    chatUser();
    setInterval(function () {
        chatUser();
    }, 300000);
    
    chatMessage(true);
    setInterval(function () {
        chatMessage(false);
    }, 5000);

    var scroll_div = $(".chat-scroll");
    scroll_div.scrollTop(scroll_div.prop('scrollHeight'));
});

function chatUser() {
    var userChat = $('.chat-user-scroll');
    $.ajax({
        url: userChat.data('url'),
        success: function (data) {
            var html = '';
            for (var i = 0; i < data.length; i++) {
                html = html + data[i].user + '<br/>';
            }
            userChat.html(html);
        }
    });
}
JS;
$this->registerJs($script);
?>