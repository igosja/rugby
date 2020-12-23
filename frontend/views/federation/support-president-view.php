<?php

// TODO refactor

use coderlex\wysibb\WysiBB;
use common\components\FormatHelper;
use common\components\HockeyHelper;
use common\models\Support;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var int $id
 * @var int $user_id
 * @var int $lazy
 * @var Support[] $supportArray
 */

print $this->render('_country');

?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="message-scroll">
                <?= Html::tag('div', '', [
                    'data' => [
                        'continue' => $lazy,
                        'limit' => Yii::$app->params['pageSizeMessage'],
                        'offset' => Yii::$app->params['pageSizeMessage'],
                        'url' => Url::to(['country/support-president-view-load', 'id' => $id, 'user_id' => $user_id]),
                    ],
                    'id' => 'lazy',
                ]); ?>
                <?php foreach ($supportArray as $support) : ?>
                    <div class="row margin-top">
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-size-3">
                            <?= FormatHelper::asDateTime($support->support_date); ?>,
                            <?= $support->support_question ? $support->user->userLink() : $support->president->userLink(); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 message <?php if ($support->support_question) : ?>message-to-me<?php else : ?>message-from-me<?php endif; ?>">
                            <?= HockeyHelper::bbDecode($support->support_text); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'errorOptions' => [
                        'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center message-error notification-error',
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
            ]); ?>
            <?= $form->field($model, 'support_text')->widget(WysiBB::class)->label('Ваше сообщение:'); ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <?= Html::submitButton('Отправить', ['class' => 'btn margin']); ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
<?php
$script = <<< JS
var lazy_in_progress = 0;
var scroll_div = $(".message-scroll");
var lazy_div = $('#lazy');

scroll_div.scrollTop(scroll_div.prop('scrollHeight'));

scroll_div.on('scroll', function() {
    if (scroll_div.scrollTop() + scroll_div.offset().top <= lazy_div.offset().top && 0 === lazy_in_progress && 1 === lazy_div.data('continue'))
    {
        lazy_in_progress = 1;

        $.ajax({
            url: lazy_div.data('url') + '?limit=' + lazy_div.data('limit') + '&offset=' + lazy_div.data('offset'),
            dataType: 'json',
            success: function (data)
            {
                var scroll_height = scroll_div.prop('scrollHeight');
                lazy_div.after(data['list']);
                lazy_div.data('offset', data['offset']);
                lazy_div.data('continue', data['lazy']);
                lazy_in_progress = 0;
                scroll_div.scrollTop(scroll_div.prop('scrollHeight') - scroll_height);
            }
        });
    }
});
JS;
$this->registerJs($script);
?>