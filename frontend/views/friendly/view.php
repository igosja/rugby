<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\FriendlyInvite;
use common\models\db\FriendlyInviteStatus;
use common\models\db\FriendlyStatus;
use common\models\db\Schedule;
use common\models\db\Team;
use rmrevin\yii\fontawesome\FAR;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var ActiveDataProvider $receivedDataProvider
 * @var ActiveDataProvider $sentDataProvider
 * @var array $scheduleStatusArray
 * @var ActiveDataProvider $scheduleDataProvider
 * @var Team $team
 * @var ActiveDataProvider $teamDataProvider
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong text-size-1">
                <?= $team->fullName() ?>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Html::a(
                    $team->friendlyStatus->name,
                    ['status']
                ) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong text-right text-size-1">
                <?= Yii::t('frontend', 'views.friendly.view.friendly') ?>
            </div>
        </div>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong">
        <?= Yii::t('frontend', 'views.friendly.view.days') ?>:
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-25'],
                'label' => Yii::t('frontend', 'views.friendly.view.th.day'),
                'value' => static function (Schedule $model) {
                    return Html::a(
                        FormatHelper::asDate($model->date),
                        ['view', 'id' => $model->id]
                    );
                }
            ],
            [
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.friendly.view.th.status'),
                'value' => static function (Schedule $model) use ($scheduleStatusArray) {
                    return $scheduleStatusArray[$model->id];
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $scheduleDataProvider,
            'emptyText' => Yii::t('frontend', 'views.friendly.view.empty'),
            'rowOptions' => static function (Schedule $model) {
                $result = [];
                if ($model->id === (int)Yii::$app->request->get('id')) {
                    $result['class'] = 'info';
                }
                return $result;
            },
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong">
        <?= Yii::t('frontend', 'views.friendly.view.invite.receive') ?>:
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-5'],
                'value' => static function (FriendlyInvite $model) {
                    if (FriendlyInviteStatus::NEW_ONE === $model->friendly_invite_status_id) {
                        return Html::a(
                                FAR::icon(FontAwesome::_CHECK_CIRCLE),
                                ['accept', 'id' => $model->id],
                                ['title' => Yii::t('frontend', 'views.friendly.view.link.accept')]
                            ) . ' ' . Html::a(
                                FAR::icon(FontAwesome::_TIMES_CIRCLE),
                                ['cancel', 'id' => $model->id],
                                ['title' => Yii::t('frontend', 'views.friendly.view.link.cancel')]
                            );
                    }
                    return '';
                }
            ],
            [
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.team'),
                'value' => static function (FriendlyInvite $model) {
                    return $model->homeTeam->getTeamImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.title.vs')],
                'label' => Yii::t('frontend', 'views.th.vs'),
                'value' => static function (FriendlyInvite $model) {
                    return $model->homeTeam->power_vs;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => [
                    'class' => 'col-10',
                    'title' => Yii::t('frontend', 'views.friendly.view.title.ratio'),
                ],
                'label' => Yii::t('frontend', 'views.friendly.view.th.ratio'),
                'value' => static function (FriendlyInvite $model) use ($team) {
                    return round($model->homeTeam->power_vs / $team->power_vs * 100) . '%';
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'col-10'],
                'label' => Yii::t('frontend', 'views.friendly.view.th.stadium'),
                'value' => static function (FriendlyInvite $model) {
                    return Yii::$app->formatter->asInteger($model->homeTeam->stadium->capacity);
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.friendly.view.title.rating')],
                'label' => Yii::t('frontend', 'views.friendly.view.th.rating'),
                'value' => static function (FriendlyInvite $model) {
                    return Yii::$app->formatter->asDecimal($model->homeTeam->visitor / 100);
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $receivedDataProvider,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong">
        <?= Yii::t('frontend', 'views.friendly.view.invite.send') ?>:
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-5'],
                'value' => static function (FriendlyInvite $model) {
                    if (FriendlyInviteStatus::NEW_ONE === $model->friendly_invite_status_id) {
                        return Html::a(
                            FAR::icon(FontAwesome::_TIMES_CIRCLE),
                            ['cancel', 'id' => $model->id],
                            ['title' => Yii::t('frontend', 'views.friendly.view.link.cancel')]
                        );
                    }
                    return '';
                }
            ],
            [
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.team'),
                'value' => static function (FriendlyInvite $model) {
                    return $model->guestTeam->getTeamImageLink();
                }
            ],
            [
                'headerOptions' => ['class' => 'col-40'],
                'label' => Yii::t('frontend', 'views.friendly.view.th.status'),
                'value' => static function (FriendlyInvite $model) {
                    return $model->friendlyInviteStatus->name;
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $sentDataProvider,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong">
        <?= Yii::t('frontend', 'views.friendly.view.opponent') ?>:
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-5'],
                'value' => static function (Team $model) {
                    return Html::a(
                        FAR::icon(FontAwesome::_CHECK_CIRCLE),
                        ['send', 'id' => Yii::$app->request->get('id'), 'teamId' => $model->id],
                        ['title' => Yii::t('frontend', 'views.friendly.view.link.send')]
                    );
                }
            ],
            [
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.team'),
                'value' => static function (Team $model) {
                    return $model->getTeamImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.title.vs')],
                'label' => Yii::t('frontend', 'views.th.vs'),
                'value' => static function (Team $model) {
                    return $model->power_vs;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => [
                    'class' => 'col-10',
                    'title' => Yii::t('frontend', 'views.friendly.view.title.ratio'),
                ],
                'label' => Yii::t('frontend', 'views.friendly.view.th.ratio'),
                'value' => static function (Team $model) use ($team) {
                    return round($model->power_vs / $team->power_vs * 100) . '%';
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.friendly.view.title.rating')],
                'label' => Yii::t('frontend', 'views.friendly.view.th.rating'),
                'value' => static function (Team $model) {
                    return Yii::$app->formatter->asDecimal($model->visitor / 100);
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $teamDataProvider,
            'rowOptions' => static function (Team $model) {
                if ($model->friendly_status_id === FriendlyStatus::ALL) {
                    return ['class' => 'success', 'title' => Yii::t('frontend', 'views.friendly.view.title.now')];
                }
                return [];
            },
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
