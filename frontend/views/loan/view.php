<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Loan;
use common\models\db\LoanApplication;
use common\models\db\User;
use common\models\db\UserBlock;
use frontend\models\forms\LoanVote;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/**
 * @var ActiveDataProvider $applicationDataProvider
 * @var ActiveDataProvider $commentDataProvider
 * @var LoanVote $model
 * @var Loan $loan
 * @var User $user
 * @var UserBlock $userBlockComment
 * @var UserBlock $userBlockDeal
 */

$user = Yii::$app->user->identity;

?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1>
                Арендная сделка
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <?= $this->render('//loan/_links') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                    Игрок:
                </div>
                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                    <?= $loan->player->country->getImage() ?>
                    <?= $loan->player->getPlayerLink() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                    Возраст:
                </div>
                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                    <?= $loan->age ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                    Позиция:
                </div>
                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                    <?= $loan->position() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                    Сила:
                </div>
                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                    <?= $loan->power ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                    Спецвозможности:
                </div>
                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                    <?= $loan->special() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                    Оценка сделки (+/-):
                </div>
                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                    <?= $loan->rating() ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                    Дата аренды:
                </div>
                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                    <?= FormatHelper::asDate($loan->ready) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                    Стоимость аренды:
                </div>
                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong <?php if ($loan->cancel) : ?>del<?php endif ?>">
                    <?= FormatHelper::asCurrency($loan->price_buyer) ?>
                    (<?= $loan->day ?> дн.)
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                    Арендодатель (команда):
                </div>
                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                    <?= $loan->teamSeller->getTeamImageLink() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                    Арендодатель (менеджер):
                </div>
                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                    <?= $loan->userSeller->getUserLink() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                    Арендатор (команда):
                </div>
                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                    <?= $loan->teamBuyer->getTeamImageLink() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 text-right">
                    Арендатор (менеджер):
                </div>
                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6 strong">
                    <?= $loan->userBuyer->getUserLink() ?>
                </div>
            </div>
        </div>
    </div>
<?php if (!$loan->voted) : ?>
    <?php foreach ($loan->alerts() as $class => $alert) : ?>
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
                    'footer' => 'Команда',
                    'format' => 'raw',
                    'label' => 'Команда',
                    'value' => static function (LoanApplication $model) {
                        return $model->team->getTeamImageLink();
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => 'Менеджер',
                    'footerOptions' => ['class' => 'hidden-xs'],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'hidden-xs'],
                    'label' => 'Менеджер',
                    'value' => static function (LoanApplication $model) {
                        return $model->user->getUserLink();
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Время',
                    'label' => 'Время',
                    'value' => static function (LoanApplication $model) {
                        return FormatHelper::asDateTime($model->date);
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Дней',
                    'label' => 'Дней',
                    'value' => static function (LoanApplication $model) {
                        return $model->day;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-right'],
                    'footer' => 'Цена',
                    'label' => 'Цена',
                    'value' => static function (LoanApplication $model) {
                        return FormatHelper::asCurrency($model->price * $model->day);
                    }
                ],
                [
                    'footer' => 'Примечание',
                    'label' => 'Примечание',
                    'value' => static function (LoanApplication $model) {
                        return $model->dealReason->text ?? '';
                    }
                ],
            ];
            print GridView::widget([
                'columns' => $columns,
                'dataProvider' => $applicationDataProvider,
                'rowOptions' => static function (LoanApplication $model) use ($loan) {
                    if ($model->team_id === $loan->team_buyer_id) {
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
            <span class="strong">Последние комментарии:</span>
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
<?php if (!$loan->voted && !Yii::$app->user->isGuest) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center strong">
            Ваше мнение:
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
            [1 => 'Честная сделка', -1 => 'Нечестная сделка'],
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
                Вам заблокирован доступ к комментированию сделок
                <br/>
                Причина - ваш почтовый адрес не подтверждён
            </div>
        </div>
    <?php elseif ($userBlockDeal && $userBlockDeal->date >= time()) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                Вам заблокирован доступ к форуму до
                <?= FormatHelper::asDatetime($userBlockDeal->date) ?>
                <br/>
                Причина - <?= $userBlockDeal->userBlockReason->text ?>
            </div>
        </div>
    <?php elseif ($userBlockComment && $userBlockComment->date >= time()) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                Вам заблокирован доступ к форуму до
                <?= FormatHelper::asDateTime($userBlockComment->date) ?>
                <br/>
                Причина - <?= $userBlockComment->userBlockReason->text ?>
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
            <?= Html::submitButton('Сохранить', ['class' => 'btn margin']) ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
<?php endif ?>