<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\History;
use common\models\db\National;
use common\models\db\Team;
use common\models\db\User;
use common\models\db\UserRating;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var ActiveDataProvider $historyDataProvider
 * @var ActiveDataProvider $nationalDataProvider
 * @var ActiveDataProvider $ratingDataProvider
 * @var ActiveDataProvider $teamDataProvider
 * @var User $user
 * @var UserRating $userRating
 */

$user = Yii::$app->user->identity;

print $this->render('//user/_top');

?>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//user/_user-links') ?>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'footer' => Yii::t('frontend', 'views.th.team'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.team'),
                'value' => static function (National $model) {
                    $name = $model->federation->country->name . ', ' . $model->nationalType->name;
                    if ((int)Yii::$app->request->get('id') === $model->vice_user_id) {
                        $name .= Yii::t('frontend', 'views.user.view.vice');
                    }
                    return Html::a(
                        $name,
                        ['national/view', 'id' => $model->id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.user.view.th.division'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-20 hidden-xs'],
                'label' => Yii::t('frontend', 'views.user.view.th.division'),
                'value' => static function (National $model) {
                    return $model->worldCup->division->name;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.vs'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.vs')],
                'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.title.vs')],
                'label' => Yii::t('frontend', 'views.th.vs'),
                'value' => static function (National $model) {
                    return $model->power_vs;
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $nationalDataProvider,
            'showFooter' => true,
            'showOnEmpty' => false,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'footer' => Yii::t('frontend', 'views.th.team'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.team'),
                'value' => static function (Team $model) {
                    return $model->getTeamImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.user.view.th.division'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-20 hidden-xs'],
                'label' => Yii::t('frontend', 'views.user.view.th.division'),
                'value' => static function (Team $model) {
                    return $model->division();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.vs'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.vs')],
                'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.title.vs')],
                'label' => Yii::t('frontend', 'views.th.vs'),
                'value' => static function (Team $model) {
                    return $model->power_vs;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-right'],
                'footer' => Yii::t('frontend', 'views.user.view.th.price'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'col-20 hidden-xs'],
                'label' => Yii::t('frontend', 'views.user.view.th.price'),
                'value' => static function (Team $model) {
                    return FormatHelper::asCurrency($model->price_total);
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $teamDataProvider,
            'showFooter' => true,
            'showOnEmpty' => false,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<?php if (Yii::$app->user->id === (int)Yii::$app->request->get('id')) : ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= Html::a(
                Yii::t('frontend', 'views.user.view.link.delete'),
                ['user/delete']
            ) ?>
            <?php if ($user->teams) : ?>
                |
                <?= Html::a(
                    Yii::t('frontend', 'views.user.view.link.re-register'),
                    ['user/re-register']
                ) ?>
                |
                <?= Html::a(
                    Yii::t('frontend', 'views.user.view.link.drop'),
                    ['user/drop-team']
                ) ?>
            <?php endif ?>
        </div>
    </div>
<?php endif ?>
<div class="row margin-top-small">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.season'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.season')],
                'headerOptions' => ['class' => 'col-3', 'title' => Yii::t('frontend', 'views.title.season')],
                'label' => Yii::t('frontend', 'views.th.season'),
                'value' => static function (UserRating $model) {
                    return $model->season_id;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => $userRating->rating,
                'label' => Yii::t('frontend', 'views.user.view.th.rating'),
                'value' => static function (UserRating $model) {
                    return $model->rating;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => $userRating->game,
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.game')],
                'headerOptions' => ['class' => 'col-3-5', 'title' => Yii::t('frontend', 'views.title.game')],
                'label' => Yii::t('frontend', 'views.th.game'),
                'value' => static function (UserRating $model) {
                    return $model->game;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => $userRating->win,
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.win')],
                'headerOptions' => ['class' => 'col-3-5', 'title' => Yii::t('frontend', 'views.title.win')],
                'label' => Yii::t('frontend', 'views.th.win'),
                'value' => static function (UserRating $model) {
                    return $model->win;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => $userRating->draw,
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.draw')],
                'headerOptions' => ['class' => 'col-3-5', 'title' => Yii::t('frontend', 'views.title.draw')],
                'label' => Yii::t('frontend', 'views.th.draw'),
                'value' => static function (UserRating $model) {
                    return $model->draw;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => $userRating->loose,
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.loose')],
                'headerOptions' => ['class' => 'col-3-5', 'title' => Yii::t('frontend', 'views.title.loose')],
                'label' => Yii::t('frontend', 'views.th.loose'),
                'value' => static function (UserRating $model) {
                    return $model->loose;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => $userRating->collision_win,
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.collision.win')],
                'headerOptions' => ['class' => 'col-3-5 hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.collision.win')],
                'label' => Yii::t('frontend', 'views.user.view.th.collision.win'),
                'value' => static function (UserRating $model) {
                    return $model->collision_win;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => $userRating->collision_loose,
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.collision.loose')],
                'headerOptions' => ['class' => 'col-3-5 hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.collision.loose')],
                'label' => Yii::t('frontend', 'views.user.view.th.collision.loose'),
                'value' => static function (UserRating $model) {
                    return $model->collision_loose;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => $userRating->win_super,
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.win.super')],
                'headerOptions' => ['class' => 'col-3-5 hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.win.super')],
                'label' => Yii::t('frontend', 'views.user.view.th.win.super'),
                'value' => static function (UserRating $model) {
                    return $model->win_super;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-sm hidden-xs text-center'],
                'footer' => $userRating->win_strong,
                'footerOptions' => ['class' => 'hidden-sm hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.win.strong')],
                'headerOptions' => ['class' => 'col-3-5 hidden-sm hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.win.strong')],
                'label' => Yii::t('frontend', 'views.user.view.th.win.strong'),
                'value' => static function (UserRating $model) {
                    return $model->win_strong;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-sm hidden-xs text-center'],
                'footer' => $userRating->win_equal,
                'footerOptions' => ['class' => 'hidden-sm hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.win.equal')],
                'headerOptions' => ['class' => 'col-3-5 hidden-sm hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.win.equal')],
                'label' => Yii::t('frontend', 'views.user.view.th.win.equal'),
                'value' => static function (UserRating $model) {
                    return $model->win_equal;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-sm hidden-xs text-center'],
                'footer' => $userRating->win_weak,
                'footerOptions' => ['class' => 'hidden-sm hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.win.weak')],
                'headerOptions' => ['class' => 'col-3-5 hidden-sm hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.win.weak')],
                'label' => Yii::t('frontend', 'views.user.view.th.win.weak'),
                'value' => static function (UserRating $model) {
                    return $model->win_weak;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-sm hidden-xs text-center'],
                'footer' => $userRating->draw_strong,
                'footerOptions' => [
                    'class' => 'hidden-sm hidden-xs',
                    'title' => Yii::t('frontend', 'views.user.view.title.draw.strong'),
                ],
                'headerOptions' => [
                    'class' => 'col-3-5 hidden-sm hidden-xs',
                    'title' => Yii::t('frontend', 'views.user.view.title.draw.strong'),
                ],
                'label' => Yii::t('frontend', 'views.user.view.th.draw.strong'),
                'value' => static function (UserRating $model) {
                    return $model->draw_strong;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-sm hidden-xs text-center'],
                'footer' => $userRating->draw_equal,
                'footerOptions' => [
                    'class' => 'hidden-sm hidden-xs',
                    'title' => Yii::t('frontend', 'views.user.view.title.draw.equal'),
                ],
                'headerOptions' => [
                    'class' => 'col-3-5 hidden-sm hidden-xs',
                    'title' => Yii::t('frontend', 'views.user.view.title.draw.equal'),
                ],
                'label' => Yii::t('frontend', 'views.user.view.th.draw.equal'),
                'value' => static function (UserRating $model) {
                    return $model->draw_equal;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-sm hidden-xs text-center'],
                'footer' => $userRating->draw_weak,
                'footerOptions' => [
                    'class' => 'hidden-sm hidden-xs',
                    'title' => Yii::t('frontend', 'views.user.view.title.draw.weak'),
                ],
                'headerOptions' => [
                    'class' => 'col-3-5 hidden-sm hidden-xs',
                    'title' => Yii::t('frontend', 'views.user.view.title.draw.weak'),
                ],
                'label' => Yii::t('frontend', 'views.user.view.th.draw.weak'),
                'value' => static function (UserRating $model) {
                    return $model->draw_weak;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-sm hidden-xs text-center'],
                'footer' => $userRating->loose_strong,
                'footerOptions' => ['class' => 'hidden-sm hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.loose.strong')],
                'headerOptions' => [
                    'class' => 'col-3-5 hidden-sm hidden-xs',
                    'title' => Yii::t('frontend', 'views.user.view.title.loose.strong')
                ],
                'label' => Yii::t('frontend', 'views.user.view.th.loose.strong'),
                'value' => static function (UserRating $model) {
                    return $model->loose_strong;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-sm hidden-xs text-center'],
                'footer' => $userRating->loose_equal,
                'footerOptions' => ['class' => 'hidden-sm hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.loose.equal')],
                'headerOptions' => ['class' => 'col-3-5 hidden-sm hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.loose.equal')],
                'label' => Yii::t('frontend', 'views.user.view.th.loose.equal'),
                'value' => static function (UserRating $model) {
                    return $model->loose_equal;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-sm hidden-xs text-center'],
                'footer' => $userRating->loose_weak,
                'footerOptions' => ['class' => 'hidden-sm hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.loose.weak')],
                'headerOptions' => ['class' => 'col-3-5 hidden-sm hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.loose.weak')],
                'label' => Yii::t('frontend', 'views.user.view.th.loose.weak'),
                'value' => static function (UserRating $model) {
                    return $model->loose_weak;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => $userRating->loose_super,
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.loose.super')],
                'headerOptions' => ['class' => 'col-3-5 hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.loose.super')],
                'label' => Yii::t('frontend', 'views.user.view.th.loose.super'),
                'value' => static function (UserRating $model) {
                    return $model->loose_super;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => $userRating->auto,
                'footerOptions' => ['title' => Yii::t('frontend', 'views.user.view.title.auto')],
                'headerOptions' => ['class' => 'col-3-5', 'title' => Yii::t('frontend', 'views.user.view.title.auto')],
                'label' => Yii::t('frontend', 'views.user.view.th.auto'),
                'value' => static function (UserRating $model) {
                    return $model->auto;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => $userRating->vs_super,
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.vs.super')],
                'headerOptions' => ['class' => 'col-3-5 hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.vs.super')],
                'label' => Yii::t('frontend', 'views.user.view.th.vs.super'),
                'value' => static function (UserRating $model) {
                    return $model->vs_super;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => $userRating->vs_rest,
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.vs.rest')],
                'headerOptions' => ['class' => 'col-3-5 hidden-xs', 'title' => Yii::t('frontend', 'views.user.view.title.vs.rest')],
                'label' => Yii::t('frontend', 'views.user.view.th.vs.rest'),
                'value' => static function (UserRating $model) {
                    return $model->vs_rest;
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $ratingDataProvider,
            'showFooter' => true,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<div class="row margin-top-small">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.season'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.season')],
                'headerOptions' => ['class' => 'col-1', 'title' => Yii::t('frontend', 'views.title.season')],
                'label' => Yii::t('frontend', 'views.th.season'),
                'value' => static function (History $model) {
                    return $model->season_id;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.date'),
                'headerOptions' => ['class' => 'col-15'],
                'label' => Yii::t('frontend', 'views.th.date'),
                'value' => static function (History $model) {
                    return FormatHelper::asDate($model->date);
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.user.view.th.text'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.user.view.th.text'),
                'value' => static function (History $model) {
                    return $model->text();
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $historyDataProvider,
            'showFooter' => true,
            'showOnEmpty' => false,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
