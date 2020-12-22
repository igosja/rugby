<?php

// TODO refactor

/**
 * @var AbstractController $controller
 * @var National $national
 * @var View $this
 */

use common\components\helpers\FormatHelper;
use common\models\db\National;
use frontend\controllers\AbstractController;
use yii\helpers\Html;
use yii\web\View;

?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-small">
        <?php if (Yii::$app->user->isGuest) : ?>
            <?= Html::a(
                Html::img(
                    '/img/roster/questionnaire.png',
                    ['alt' => 'Зарегистрироваться', 'title' => 'Зарегистрироваться']
                ),
                ['site/sign-up'],
                ['class' => 'no-underline']
            ) ?>
        <?php else: ?>
            <?php if ($national->myTeam()) : ?>
                <?= Html::a(
                    Html::img(
                        '/img/roster/substitute.png',
                        ['alt' => 'Изменить состав сборной', 'title' => 'Изменить состав сборной']
                    ),
                    ['national/player', 'id' => $national->id],
                    ['class' => 'no-underline']
                ) ?>
            <?php endif ?>
            <?php if ((Yii::$app->user->id === $national->user_id && $national->vice_user_id) || Yii::$app->user->id === $national->vice_user_id) : ?>
                <?= Html::a(
                    Html::img(
                        '/img/roster/fire.png',
                        ['alt' => 'Отказаться от должности', 'title' => 'Отказаться от должности']
                    ),
                    ['national/fire', 'id' => $national->id],
                    ['class' => 'no-underline']
                ) ?>
            <?php endif ?>
            <?= Html::a(
                Html::img(
                    '/img/roster/questionnaire.png',
                    ['alt' => 'Личные данные', 'title' => 'Личные данные']
                ),
                ['user/questionnaire'],
                ['class' => 'no-underline']
            ) ?>
        <?php endif ?>
    </div>
    <?php foreach ($national->latestGame() as $item) : ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3 italic">
            <?= FormatHelper::asDatetime($item->schedule->date) ?>
            -
            <?= $item->schedule->tournamentType->name ?>
            -
            <?= $item->home_national_id === $national->id ? 'Д' : 'Г' ?>
            -
            <?= Html::a(
                $item->home_national_id === $national->id ? $item->guestNational->federation->country->name : $item->homeNational->federation->country->name,
                ['national/view', 'id' => $item->home_national_id === $national->id ? $item->guest_national_id : $item->home_national_id]
            ) ?>
            -
            <?= Html::a(
                $item->home_national_id === $national->id ? $item->home_point . ':' . $item->guest_point : $item->guest_point . ':' . $item->home_point,
                ['game/view', 'id' => $item->id]
            ) ?>
        </div>
    <?php endforeach ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row text-size-4">&nbsp;</div>
    </div>
    <?php foreach ($national->nearestGame() as $item) : ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3 italic">
            <?= FormatHelper::asDatetime($item->schedule->date) ?>
            -
            <?= $item->schedule->tournamentType->name ?>
            -
            <?= $item->home_national_id === $national->id ? 'Д' : 'Г' ?>
            -
            <?= Html::a(
                $item->home_national_id === $national->id ? $item->guestNational->federation->country->name : $item->homeNational->federation->country->name,
                ['national/view', 'id' => $item->home_national_id === $national->id ? $item->guest_national_id : $item->home_national_id]
            ) ?>
            -
            <?php if ($national->myTeamOrVice()) : ?>
                <?= Html::a(
                    (($item->home_national_id === $national->id && $item->home_tactic_id)
                        || ($item->guest_national_id === $national->id && $item->guest_tactic_id))
                        ? 'Ред.'
                        : 'Отпр.',
                    ['lineup-national/view', 'id' => $item->id]
                ) ?>
            <?php else: ?>
                <?= Html::a(
                    '?:?',
                    ['game/preview', 'id' => $item->id]
                ) ?>
            <?php endif ?>
        </div>
    <?php endforeach ?>
</div>
