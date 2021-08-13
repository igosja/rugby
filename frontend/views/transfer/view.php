<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Transfer;
use common\models\db\TransferApplication;
use common\models\db\User;
use common\models\db\UserBlock;
use frontend\models\forms\TransferVote;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/**
 * @var ActiveDataProvider $applicationDataProvider
 * @var ActiveDataProvider $commentDataProvider
 * @var TransferVote $model
 * @var Transfer $transfer
 * @var User $user
 * @var UserBlock $userBlockComment
 * @var UserBlock $userBlockDeal
 */

$user = Yii::$app->user->identity;

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1>
            <?= Yii::t('frontend', 'views.transfer.view.h1') ?>
        </h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $this->render('//transfer/_links') ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                <?= Yii::t('frontend', 'views.transfer.view.player') ?>:
            </div>
            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                <?= $transfer->player->country->getImage() ?>
                <?= $transfer->player->getPlayerLink() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                <?= Yii::t('frontend', 'views.transfer.view.age') ?>:
            </div>
            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                <?= $transfer->age ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                <?= Yii::t('frontend', 'views.transfer.view.position') ?>:
            </div>
            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                <?= $transfer->position() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                <?= Yii::t('frontend', 'views.transfer.view.power') ?>:
            </div>
            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                <?= $transfer->power ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                <?= Yii::t('frontend', 'views.transfer.view.special') ?>:
            </div>
            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                <?= $transfer->special() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                <?= Yii::t('frontend', 'views.transfer.view.rating') ?>:
            </div>
            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                <?= $transfer->rating() ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                <?= Yii::t('frontend', 'views.transfer.view.ready') ?>:
            </div>
            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                <?= FormatHelper::asDate($transfer->ready) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                <?= Yii::t('frontend', 'views.transfer.view.price') ?>:
            </div>
            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong <?php if ($transfer->cancel) : ?>del<?php endif ?>">
                <?= FormatHelper::asCurrency($transfer->price_buyer) ?>
                (<?= round($transfer->price_buyer / $transfer->player_price * 100) ?>%)
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                <?= Yii::t('frontend', 'views.transfer.view.team-seller') ?>:
            </div>
            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                <?= $transfer->teamSeller->getTeamLink() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                <?= Yii::t('frontend', 'views.transfer.view.user-seller') ?>:
            </div>
            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                <?= $transfer->userSeller->getUserLink() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                <?= Yii::t('frontend', 'views.transfer.view.buyer-team') ?>:
            </div>
            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                <?= $transfer->teamBuyer->getTeamLink() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                <?= Yii::t('frontend', 'views.transfer.view.buyer-user') ?>:
            </div>
            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                <?= $transfer->userBuyer->getUserLink() ?>
            </div>
        </div>
    </div>
</div>
<?php if (!$transfer->voted) : ?>
    <?php foreach ($transfer->alerts() as $class => $alert) : ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 alert <?= $class ?> margin-top-small">
                <ul>
                    <?php foreach ($alert as $item) : ?>
                        <li><?= $item ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    <?php endforeach ?>
<?php endif ?>
    <div class="row margin-top">
        <?php

        try {
            $columns = [
                [
                    'footer' => Yii::t('frontend', 'views.transfer.view.application.team'),
                    'format' => 'raw',
                    'label' => Yii::t('frontend', 'views.transfer.view.application.team'),
                    'value' => static function (TransferApplication $model) {
                        return $model->team->getTeamLink();
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => Yii::t('frontend', 'views.transfer.view.application.user'),
                    'footerOptions' => ['class' => 'hidden-xs'],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'hidden-xs'],
                    'label' => Yii::t('frontend', 'views.transfer.view.application.user'),
                    'value' => static function (TransferApplication $model) {
                        return $model->user->getUserLink();
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.transfer.view.application.date'),
                    'label' => Yii::t('frontend', 'views.transfer.view.application.date'),
                    'value' => static function (TransferApplication $model) {
                        return FormatHelper::asDateTime($model->date);
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-right'],
                    'footer' => Yii::t('frontend', 'views.transfer.view.application.price'),
                    'label' => Yii::t('frontend', 'views.transfer.view.application.price'),
                    'value' => static function (TransferApplication $model) {
                        return FormatHelper::asCurrency($model->price);
                    }
                ],
                [
                    'footer' => Yii::t('frontend', 'views.transfer.view.application.text'),
                    'label' => Yii::t('frontend', 'views.transfer.view.application.text'),
                    'value' => static function (TransferApplication $model) {
                        return $model->dealReason->text ?? '';
                    }
                ],
            ];
            print GridView::widget([
                'columns' => $columns,
                'dataProvider' => $applicationDataProvider,
                'rowOptions' => static function (TransferApplication $model) use ($transfer) {
                    if ($model->team_id === $transfer->team_buyer_id) {
                        return ['class' => 'info'];
                    }
                    return [];
                },
                'showFooter' => true,
            ]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
    </div>
<?= $this->render('//site/_show-full-table') ?>
<?php if ($commentDataProvider->models) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <span class="strong"><?= Yii::t('frontend', 'views.transfer.view.comments') ?>:</span>
        </div>
    </div>
    <div class="row">
        <?php

        try {
            print ListView::widget([
                'dataProvider' => $commentDataProvider,
                'itemOptions' => ['class' => 'row border-top'],
                'itemView' => '_comment',
            ]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
    </div>
<?php endif ?>
<?php if (!$transfer->voted && !Yii::$app->user->isGuest) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center strong">
            <?= Yii::t('frontend', 'views.transfer.view.your-opinion') ?>:
        </div>
    </div>
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'errorOptions' => [
                'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 notification-error',
                'tag' => 'div'
            ],
        ],
    ]) ?>
    <?= $form
        ->field($model, 'vote')
        ->radioList(
            [1 => Yii::t('frontend', 'views.transfer.view.vote-plus'), -1 => Yii::t('frontend', 'views.transfer.view.vote-minus')],
            [
                'item' => static function ($index, $label, $name, $checked, $value) {
                    return Html::radio($name, $checked, [
                            'index' => $index,
                            'label' => $label,
                            'value' => $value,
                        ]) . '<br/>';
                }
            ]
        )
        ->label(false) ?>
    <?php if (!$user->date_confirm) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                <?= Yii::t('frontend', 'views.transfer.view.blocked-confirm') ?>
            </div>
        </div>
    <?php elseif ($userBlockDeal && $userBlockDeal->date >= time()) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                <?= Yii::t('frontend', 'views.transfer.view.blocked-reason', [
                    'date' => FormatHelper::asDatetime($userBlockDeal->date),
                    'reason' => $userBlockDeal->userBlockReason->text,
                ]) ?>
            </div>
        </div>
    <?php elseif ($userBlockComment && $userBlockComment->date >= time()) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                <?= Yii::t('frontend', 'views.transfer.view.blocked-reason', [
                    'date' => FormatHelper::asDatetime($userBlockComment->date),
                    'reason' => $userBlockComment->userBlockReason->text,
                ]) ?>
            </div>
        </div>
    <?php else: ?>
        <br/>
        <div class="row margin-top">
            <?= $form->field($model, 'comment')->textarea()->label(false) ?>
        </div>
    <?php endif ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <?= Html::submitButton(Yii::t('frontend', 'views.transfer.view.submit'), ['class' => 'btn margin']) ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
<?php endif ?>

