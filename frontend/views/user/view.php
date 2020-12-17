<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Federation;
use common\models\db\History;
use common\models\db\National;
use common\models\db\Team;
use common\models\db\User;
use common\models\db\UserRating;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var ActiveDataProvider $countryDataProvider
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
                'footer' => 'Роль в игре',
                'format' => 'raw',
                'label' => 'Роль в игре',
                'value' => static function (Federation $model) {
                    $result = $model->country->name . ' ';
                    if ((int)Yii::$app->request->get('id') === $model->president_user_id) {
                        $result .= '(Президент федерации)';
                    } else {
                        $result .= '(Заместитель президента федерации)';
                    }
                    return Html::a($result, ['federation/news', 'id' => $model->id]);
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $countryDataProvider,
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
                'footer' => 'Команда',
                'format' => 'raw',
                'label' => 'Команда',
                'value' => static function (National $model) {
                    $name = $model->federation->country->name . ', ' . $model->nationalType->name;
                    if ((int)Yii::$app->request->get('id') === $model->vice_user_id) {
                        $name .= ' (заместитель)';
                    }
                    return Html::a(
                        $name,
                        ['national/view', 'id' => $model->id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'Дивизион',
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-20 hidden-xs'],
                'label' => 'Дивизион',
                'value' => static function (National $model) {
                    return $model->worldCup->division->name;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Vs',
                'footerOptions' => ['title' => 'Рейтинг силы команды в длительных соревнованиях'],
                'headerOptions' => ['class' => 'col-10', 'title' => 'Рейтинг силы команды в длительных соревнованиях'],
                'label' => 'Vs',
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
                'footer' => 'Команда',
                'format' => 'raw',
                'label' => 'Команда',
                'value' => static function (Team $model) {
                    return $model->getTeamImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'Дивизион',
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-20 hidden-xs'],
                'label' => 'Дивизион',
                'value' => static function (Team $model) {
                    return $model->division();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Vs',
                'footerOptions' => ['title' => 'Рейтинг силы команды в длительных соревнованиях'],
                'headerOptions' => ['class' => 'col-10', 'title' => 'Рейтинг силы команды в длительных соревнованиях'],
                'label' => 'Vs',
                'value' => static function (Team $model) {
                    return $model->power_vs;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-right'],
                'footer' => 'Стоимость',
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'col-20 hidden-xs'],
                'label' => 'Стоимость',
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
                'Удалить аккаунт',
                ['user/delete']
            ) ?>
            <?php if ($user->teams) : ?>
                |
                <?= Html::a(
                    'Перерегистрировать команду',
                    ['user/re-register']
                ) ?>
                |
                <?= Html::a(
                    'Отказаться от команды',
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
                'footer' => 'C',
                'footerOptions' => ['title' => 'Сезон'],
                'headerOptions' => ['class' => 'col-3', 'title' => 'Сезон'],
                'label' => 'C',
                'value' => static function (UserRating $model) {
                    return $model->season_id;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => $userRating->rating,
                'label' => 'Рейтинг',
                'value' => static function (UserRating $model) {
                    return $model->rating;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => $userRating->game,
                'footerOptions' => ['title' => 'Игры'],
                'headerOptions' => ['class' => 'col-3-5', 'title' => 'Игры'],
                'label' => 'И',
                'value' => static function (UserRating $model) {
                    return $model->game;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => $userRating->win,
                'footerOptions' => ['title' => 'Победы'],
                'headerOptions' => ['class' => 'col-3-5', 'title' => 'Победы'],
                'label' => 'B',
                'value' => static function (UserRating $model) {
                    return $model->win;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => $userRating->draw,
                'footerOptions' => ['title' => 'Ничьи'],
                'headerOptions' => ['class' => 'col-3-5', 'title' => 'Ничьи'],
                'label' => 'Н',
                'value' => static function (UserRating $model) {
                    return $model->draw;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => $userRating->loose,
                'footerOptions' => ['title' => 'Поражения'],
                'headerOptions' => ['class' => 'col-3-5', 'title' => 'Поражения'],
                'label' => 'П',
                'value' => static function (UserRating $model) {
                    return $model->loose;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => $userRating->collision_win,
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Выигранные коллизии'],
                'headerOptions' => ['class' => 'col-3-5 hidden-xs', 'title' => 'Выигранные коллизии'],
                'label' => 'К+',
                'value' => static function (UserRating $model) {
                    return $model->collision_win;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => $userRating->collision_loose,
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Проигранные коллизии'],
                'headerOptions' => ['class' => 'col-3-5 hidden-xs', 'title' => 'Проигранные коллизии'],
                'label' => 'К-',
                'value' => static function (UserRating $model) {
                    return $model->collision_loose;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => $userRating->win_super,
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Победы у команд с супернастроем'],
                'headerOptions' => ['class' => 'col-3-5 hidden-xs', 'title' => 'Победы у команд с супернастроем'],
                'label' => 'ВС',
                'value' => static function (UserRating $model) {
                    return $model->win_super;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-sm hidden-xs text-center'],
                'footer' => $userRating->win_strong,
                'footerOptions' => ['class' => 'hidden-sm hidden-xs', 'title' => 'Победы у сильных соперников'],
                'headerOptions' => ['class' => 'col-3-5 hidden-sm hidden-xs', 'title' => 'Победы у сильных соперников'],
                'label' => 'В+',
                'value' => static function (UserRating $model) {
                    return $model->win_strong;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-sm hidden-xs text-center'],
                'footer' => $userRating->win_equal,
                'footerOptions' => ['class' => 'hidden-sm hidden-xs', 'title' => 'Победы у равных соперников'],
                'headerOptions' => ['class' => 'col-3-5 hidden-sm hidden-xs', 'title' => 'Победы у равных соперников'],
                'label' => 'В=',
                'value' => static function (UserRating $model) {
                    return $model->win_equal;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-sm hidden-xs text-center'],
                'footer' => $userRating->win_weak,
                'footerOptions' => ['class' => 'hidden-sm hidden-xs', 'title' => 'Победы у слабых соперников'],
                'headerOptions' => ['class' => 'col-3-5 hidden-sm hidden-xs', 'title' => 'Победы у слабых соперников'],
                'label' => 'В-',
                'value' => static function (UserRating $model) {
                    return $model->win_weak;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-sm hidden-xs text-center'],
                'footer' => $userRating->draw_strong,
                'footerOptions' => [
                    'class' => 'hidden-sm hidden-xs',
                    'title' => 'Ничьи с сильными соперниками',
                ],
                'headerOptions' => [
                    'class' => 'col-3-5 hidden-sm hidden-xs',
                    'title' => 'Ничьи с сильными соперниками',
                ],
                'label' => 'ВО+',
                'value' => static function (UserRating $model) {
                    return $model->draw_strong;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-sm hidden-xs text-center'],
                'footer' => $userRating->draw_equal,
                'footerOptions' => [
                    'class' => 'hidden-sm hidden-xs',
                    'title' => 'Ничьи с равными соперниками',
                ],
                'headerOptions' => [
                    'class' => 'col-3-5 hidden-sm hidden-xs',
                    'title' => 'Ничьи с равными соперниками',
                ],
                'label' => 'ВО=',
                'value' => static function (UserRating $model) {
                    return $model->draw_equal;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-sm hidden-xs text-center'],
                'footer' => $userRating->draw_weak,
                'footerOptions' => [
                    'class' => 'hidden-sm hidden-xs',
                    'title' => 'Ничьи со слабыми соперниками',
                ],
                'headerOptions' => [
                    'class' => 'col-3-5 hidden-sm hidden-xs',
                    'title' => 'Ничьи со слабыми соперниками',
                ],
                'label' => 'ВО-',
                'value' => static function (UserRating $model) {
                    return $model->draw_weak;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-sm hidden-xs text-center'],
                'footer' => $userRating->loose_strong,
                'footerOptions' => ['class' => 'hidden-sm hidden-xs', 'title' => 'Поражения сильным соперникам'],
                'headerOptions' => [
                    'class' => 'col-3-5 hidden-sm hidden-xs',
                    'title' => 'Поражения сильным соперникам'
                ],
                'label' => 'П+',
                'value' => static function (UserRating $model) {
                    return $model->loose_strong;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-sm hidden-xs text-center'],
                'footer' => $userRating->loose_equal,
                'footerOptions' => ['class' => 'hidden-sm hidden-xs', 'title' => 'Поражения равным соперникам'],
                'headerOptions' => ['class' => 'col-3-5 hidden-sm hidden-xs', 'title' => 'Поражения равным соперникам'],
                'label' => 'П=',
                'value' => static function (UserRating $model) {
                    return $model->loose_equal;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-sm hidden-xs text-center'],
                'footer' => $userRating->loose_weak,
                'footerOptions' => ['class' => 'hidden-sm hidden-xs', 'title' => 'Поражения слабым соперникам'],
                'headerOptions' => ['class' => 'col-3-5 hidden-sm hidden-xs', 'title' => 'Поражения слабым соперникам'],
                'label' => 'П-',
                'value' => static function (UserRating $model) {
                    return $model->loose_weak;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => $userRating->loose_super,
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Поражения супернастроем'],
                'headerOptions' => ['class' => 'col-3-5 hidden-xs', 'title' => 'Поражения супернастроем'],
                'label' => 'ПС',
                'value' => static function (UserRating $model) {
                    return $model->loose_super;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => $userRating->auto,
                'footerOptions' => ['title' => 'Автосоставы'],
                'headerOptions' => ['class' => 'col-3-5', 'title' => 'Автосоставы'],
                'label' => 'А',
                'value' => static function (UserRating $model) {
                    return $model->auto;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => $userRating->vs_super,
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Игры против супернастроя'],
                'headerOptions' => ['class' => 'col-3-5 hidden-xs', 'title' => 'Игры против супернастроя'],
                'label' => 'VС',
                'value' => static function (UserRating $model) {
                    return $model->vs_super;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => $userRating->vs_rest,
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Игры против отдыха'],
                'headerOptions' => ['class' => 'col-3-5 hidden-xs', 'title' => 'Игры против отдыха'],
                'label' => 'VО',
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
                'footer' => 'С',
                'footerOptions' => ['title' => 'Сезон'],
                'headerOptions' => ['class' => 'col-1', 'title' => 'Сезон'],
                'label' => 'С',
                'value' => static function (History $model) {
                    return $model->season_id;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Дата',
                'headerOptions' => ['class' => 'col-15'],
                'label' => 'Дата',
                'value' => static function (History $model) {
                    return FormatHelper::asDate($model->date);
                }
            ],
            [
                'footer' => 'Событие',
                'format' => 'raw',
                'label' => 'Событие',
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
