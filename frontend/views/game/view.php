<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Event;
use common\models\db\EventType;
use common\models\db\Game;
use common\models\db\GameComment;
use common\models\db\Lineup;
use common\models\db\User;
use common\models\db\UserBlock;
use rmrevin\yii\fontawesome\FAB;
use rmrevin\yii\fontawesome\FAR;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/**
 * @var ActiveDataProvider $commentDataProvider
 * @var ActiveDataProvider $eventDataProvider
 * @var Game $game
 * @var GameComment $model
 * @var Lineup[] $lineupGuest
 * @var Lineup[] $lineupHome
 * @var User $user
 * @var UserBlock $userBlockComment
 * @var UserBlock $userBlockGame
 */

$user = Yii::$app->user->identity;

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <?php if (!Yii::$app->user->isGuest) : ?>
            <?= Html::a(
                FAB::i(FontAwesome::_THUMBS_UP),
                ['vote', 'id' => $game->id, 'vote' => 1],
                ['title' => Yii::t('frontend', 'views.game.view.vote-up')]
            ) ?>
        <?php endif ?>
        <span title="<?= Yii::t('frontend', 'views.game.view.title.rating') ?>"><?= $game->rating() ?></span>
        <?php if (!Yii::$app->user->isGuest) : ?>
            <?= Html::a(
                FAR::i(FontAwesome::_THUMBS_DOWN),
                ['vote', 'id' => $game->id, 'vote' => -1],
                ['title' => Yii::t('frontend', 'views.game.view.vote-down')]
            ) ?>
        <?php endif ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
        <span class="strong"><?= Yii::t('frontend', 'views.game.link.result') ?></span>
        |
        <?= Html::a(
            Yii::t('frontend', 'views.game.link.before-game'),
            ['preview', 'id' => $game->id]
        ) ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center">
                        <?= $game->home_national_id ? $game->homeNational->federation->country->getImage() : '' ?>
                        <?= $game->teamOrNationalLink('home', false) ?>
                        <?= $game->formatAuto() ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center">
                        <?= $game->home_point ?>:<?= $game->guest_point ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center">
                        <?= $game->guest_national_id ? $game->guestNational->federation->country->getImage() : '' ?>
                        <?= $game->teamOrNationalLink('guest', false) ?>
                        <?= $game->formatAuto('guest') ?>
                    </div>
                </th>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center">
        <?php if ($game->homeTeam && file_exists(Yii::getAlias('@webroot') . '/img/team/125/' . $game->home_team_id . '.png')) : ?>
            <?= Html::img(
                '/img/team/125/' . $game->home_team_id . '.png?v=' . filemtime(Yii::getAlias('@webroot') . '/img/team/125/' . $game->home_team_id . '.png'),
                [
                    'alt' => $game->homeTeam->name,
                    'class' => 'team-logo-game',
                    'title' => $game->homeTeam->name,
                ]
            ) ?>
        <?php elseif ($game->homeNational && file_exists(Yii::getAlias('@webroot') . '/img/country/100/' . $game->homeNational->federation->country_id . '.png')) : ?>
            <?= Html::img(
                '/img/country/100/' . $game->homeNational->federation->country_id . '.png?v=' . filemtime(Yii::getAlias('@webroot') . '/img/country/100/' . $game->homeNational->federation->country_id . '.png'),
                [
                    'alt' => $game->homeNational->federation->country->name,
                    'class' => 'team-logo-game',
                    'title' => $game->homeNational->federation->country->name,
                ]
            ) ?>
        <?php endif ?>
        <div>
            <?php if ($game->home_plus_minus >= 0): ?>
                <?= FAR::icon(FontAwesome::_PLUS_SQUARE, ['title' => Yii::t('frontend', 'views.game.view.point.up')])->addCssClass('font-green') ?>
            <?php else: ?>
                <?= FAR::icon(FontAwesome::_MINUS_SQUARE, ['title' => Yii::t('frontend', 'views.game.view.point.down')])->addCssClass('font-red') ?>
            <?php endif ?>
            <?= abs($game->home_plus_minus) ?>
        </div>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= FormatHelper::asDatetime($game->schedule->date) ?>,
                <?= $game->tournamentLink() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::a($game->stadium->name, ['team/view', 'id' => $game->stadium->team->id]) ?>
                (<?= $game->stadium_capacity ?>),
                <?= Yii::t('frontend', 'views.game.view.visitor') ?>: <?= $game->visitor ?>.
                <?= Yii::t('frontend', 'views.game.view.ticket') ?>
                : <?= FormatHelper::asCurrency($game->ticket_price) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center">
        <?php if ($game->guestTeam && file_exists(Yii::getAlias('@webroot') . '/img/team/125/' . $game->guest_team_id . '.png')) : ?>
            <?= Html::img(
                '/img/team/125/' . $game->guest_team_id . '.png?v=' . filemtime(Yii::getAlias('@webroot') . '/img/team/125/' . $game->guest_team_id . '.png'),
                [
                    'alt' => $game->guestTeam->name,
                    'class' => 'team-logo-game',
                    'title' => $game->guestTeam->name,
                ]
            ) ?>
        <?php elseif ($game->guestNational && file_exists(Yii::getAlias('@webroot') . '/img/country/100/' . $game->guestNational->federation->country_id . '.png')) : ?>
            <?= Html::img(
                '/img/country/100/' . $game->guestNational->federation->country_id . '.png?v=' . filemtime(Yii::getAlias('@webroot') . '/img/country/100/' . $game->guestNational->federation->country_id . '.png'),
                [
                    'alt' => $game->guestNational->federation->country->name,
                    'class' => 'team-logo-game',
                    'title' => $game->guestNational->federation->country->name,
                ]
            ) ?>
        <?php endif ?>
        <div>
            <?php if ($game->guest_plus_minus >= 0): ?>
                <?= FAR::icon(FontAwesome::_PLUS_SQUARE, ['title' => Yii::t('frontend', 'views.game.view.point.up')])->addCssClass('font-green') ?>
            <?php else: ?>
                <?= FAR::icon(FontAwesome::_MINUS_SQUARE, ['title' => Yii::t('frontend', 'views.game.view.point.down')])->addCssClass('font-red') ?>
            <?php endif ?>
            <?= abs($game->guest_plus_minus) ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-bordered">
            <tr>
                <td class="col-35 text-center">
                    <?= $game->homeTactic->name ?>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.tactic') ?></td>
                <td class="col-35 text-center">
                    <?= $game->guestTactic->name ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <span class="<?= $game->cssStyle('home') ?>">
                        <?= $game->homeStyle->name ?>
                    </span>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.style') ?></td>
                <td class="text-center">
                    <span class="<?= $game->cssStyle('guest') ?>">
                        <?= $game->guestStyle->name ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->homeRudeness->name ?>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.rudeness') ?></td>
                <td class="text-center">
                    <?= $game->guestRudeness->name ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <span class="<?= $game->cssMood('home') ?>">
                        <?= $game->homeMood->name ?>
                    </span>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.mood') ?></td>
                <td class="text-center">
                    <span class="<?= $game->cssMood('guest') ?>">
                        <?= $game->guestMood->name ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_power_percent ?>%
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.power-percent') ?></td>
                <td class="text-center">
                    <?= $game->guest_power_percent ?>%
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <span title="<?= Yii::t('frontend', 'views.game.view.optimality.1') ?>"><?= $game->home_optimality_1 ?>%</span>
                    |
                    <span title="<?= Yii::t('frontend', 'views.game.view.optimality.2') ?>"><?= $game->home_optimality_2 ?>%</span>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.optimality') ?></td>
                <td class="text-center">
                    <span title="<?= Yii::t('frontend', 'views.game.view.optimality.1') ?>"><?= $game->guest_optimality_1 ?>%</span>
                    |
                    <span title="<?= Yii::t('frontend', 'views.game.view.optimality.2') ?>"><?= $game->guest_optimality_2 ?>%</span>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_teamwork ?>%
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.teamwork') ?></td>
                <td class="text-center">
                    <?= $game->guest_teamwork ?>%
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_carry ?>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.carry') ?></td>
                <td class="text-center">
                    <?= $game->guest_carry ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_clean_break ?>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.clean-break') ?></td>
                <td class="text-center">
                    <?= $game->guest_clean_break ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_defender_beaten ?>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.defender-beaten') ?></td>
                <td class="text-center">
                    <?= $game->guest_defender_beaten ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_metre_gained ?>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.metre-gained') ?></td>
                <td class="text-center">
                    <?= $game->guest_metre_gained ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_pass ?>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.pass') ?></td>
                <td class="text-center">
                    <?= $game->guest_pass ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_penalty_conceded ?>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.penalty-conceded') ?></td>
                <td class="text-center">
                    <?= $game->guest_penalty_conceded ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_turnover_won ?>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.turnover-won') ?></td>
                <td class="text-center">
                    <?= $game->guest_turnover_won ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_try ?>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.try') ?></td>
                <td class="text-center">
                    <?= $game->guest_try ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_conversion ?>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.conversion') ?></td>
                <td class="text-center">
                    <?= $game->guest_conversion ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_penalty_kick ?>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.penalty-kick') ?></td>
                <td class="text-center">
                    <?= $game->guest_penalty_kick ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_drop_goal ?>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.drop-goal') ?></td>
                <td class="text-center">
                    <?= $game->guest_drop_goal ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_red_card ?>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.red-card') ?></td>
                <td class="text-center">
                    <?= $game->guest_red_card ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_yellow_card ?>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.yellow-card') ?></td>
                <td class="text-center">
                    <?= $game->guest_yellow_card ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_forecast ?>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.forecast') ?></td>
                <td class="text-center">
                    <?= $game->guest_forecast ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_power ?>
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.power') ?></td>
                <td class="text-center">
                    <?= $game->guest_power ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_possession ?>%
                </td>
                <td class="text-center"><?= Yii::t('frontend', 'views.game.view.possession') ?></td>
                <td class="text-center">
                    <?= $game->guest_possession ?>%
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="row margin-top">
    <?php for ($i = 0; $i < 2; $i++) : ?>
        <?php
        if (0 === $i) {
            $side = 'home';
            $lineupArray = $lineupHome;
        } else {
            $side = 'guest';
            $lineupArray = $lineupGuest;
        }
        ?>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th class="col-6"
                        title="<?= Yii::t('frontend', 'views.title.position') ?>"><?= Yii::t('frontend', 'views.th.position') ?></th>
                    <th><?= $game->teamOrNationalLink($side, false) ?></th>
                    <th class="col-6 hidden-xs"
                        title="<?= Yii::t('frontend', 'views.title.age') ?>"><?= Yii::t('frontend', 'views.th.age') ?></th>
                    <th class="col-6 hidden-xs"
                        title="<?= Yii::t('frontend', 'views.title.nominal-power') ?>"><?= Yii::t('frontend', 'views.th.nominal-power') ?></th>
                    <th class="col-6"
                        title="<?= Yii::t('frontend', 'views.title.real-power') ?>"><?= Yii::t('frontend', 'views.th.real-power') ?></th>
                    <th class="hidden-xs hidden-sm"
                        title="<?= Yii::t('frontend', 'views.title.special') ?>"><?= Yii::t('frontend', 'views.th.special') ?></th>
                    <th class="col-6"
                        title="<?= Yii::t('frontend', 'views.title.try') ?>"><?= Yii::t('frontend', 'views.th.try') ?></th>
                    <th class="col-6"
                        title="<?= Yii::t('frontend', 'views.title.conversion') ?>"><?= Yii::t('frontend', 'views.th.conversion') ?></th>
                    <th class="col-6"
                        title="<?= Yii::t('frontend', 'views.title.penalty-kick') ?>"><?= Yii::t('frontend', 'views.th.penalty-kick') ?></th>
                    <th class="col-6 hidden-xs"
                        title="<?= Yii::t('frontend', 'views.title.drop-goal') ?>"><?= Yii::t('frontend', 'views.th.drop-goal') ?></th>
                </tr>
                <?php for ($j = 0, $countLineup = count($lineupArray); $j < $countLineup; $j++) : ?>
                    <tr>
                        <td class="text-center">
                            <?= $lineupArray[$j]->position_id ?>
                        </td>
                        <td>
                            <?= $lineupArray[$j]->player->getPlayerLink() ?>
                            <?= $lineupArray[$j]->iconCaptain() ?>
                            <?php try {
                                print $lineupArray[$j]->iconPowerChange();
                            } catch (InvalidConfigException $e) {
                                ErrorHelper::log($e);
                            } ?>
                        </td>
                        <td class="hidden-xs text-center">
                            <?= $lineupArray[$j]->age ?>
                        </td>
                        <td class="hidden-xs text-center">
                            <?= $lineupArray[$j]->power_nominal ?>
                        </td>
                        <td class="text-center">
                            <?= $lineupArray[$j]->power_real ?>
                        </td>
                        <td class="text-size-2 hidden-xs hidden-sm text-center">
                            <?= $lineupArray[$j]->special() ?>
                        </td>
                        <td class="text-center">
                            <?= $lineupArray[$j]->try ?>
                        </td>
                        <td class="text-center">
                            <?= $lineupArray[$j]->conversion ?>
                        </td>
                        <td class="text-center">
                            <?= $lineupArray[$j]->penalty_kick ?>
                        </td>
                        <td class="hidden-xs text-center">
                            <?= $lineupArray[$j]->drop_goal ?>
                        </td>
                    </tr>
                <?php endfor ?>
            </table>
        </div>
    <?php endfor ?>
</div>
<?= $this->render('//site/_show-full-table') ?>
<div class="row margin-top">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'header' => Yii::t('frontend', 'views.game.view.th.time'),
                'value' => static function (Event $model) {
                    return $model->minute . "'";
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'header' => Yii::t('frontend', 'views.game.view.th.team'),
                'value' => static function (Event $model) {
                    $side = 'guest';
                    if (($model->team_id && $model->team_id === $model->game->home_team_id)
                        || ($model->national_id && $model->national_id === $model->game->home_national_id)) {
                        $side = 'home';
                    }
                    return $model->game->teamOrNationalLink($side, false);
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center hidden-xs'],
                'format' => 'raw',
                'header' => Yii::t('frontend', 'views.game.view.th.type'),
                'headerOptions' => ['class' => 'text-center hidden-xs'],
                'value' => static function (Event $model) {
                    return $model->icon();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center hidden-xs'],
                'format' => 'raw',
                'header' => Yii::t('frontend', 'views.game.view.th.event'),
                'headerOptions' => ['class' => 'hidden-xs'],
                'value' => static function (Event $model) {
                    return $model->eventText->text . ' - ' . $model->player->getPlayerLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'header' => Yii::t('frontend', 'views.game.view.th.score'),
                'value' => static function (Event $model) {
                    if ($model->eventText->event_type_id === EventType::TYPE_GOAL) {
                        return $model->home_point . ':' . $model->guest_point;
                    }
                    return '';
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $eventDataProvider,
            'summary' => false,
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
            <span class="strong"><?= Yii::t('frontend', 'views.game.view.comments') ?>:</span>
        </div>
    </div>
    <div class="row">
        <?php

        try {
            print ListView::widget([
                'dataProvider' => $commentDataProvider,
                'itemView' => '_comment',
            ]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
    </div>
<?php endif ?>
<?php if (!Yii::$app->user->isGuest) : ?>
    <?php if (!$user->date_confirm) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                <?= Yii::t('frontend', 'views.game.view.blocked-confirm') ?>
            </div>
        </div>
    <?php elseif ($userBlockGame && $userBlockGame->date >= time()) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                <?= Yii::t('frontend', 'views.game.view.blocked-reason', [
                    'date' => FormatHelper::asDatetime($userBlockGame->date),
                    'reason' => $userBlockGame->userBlockReason->text,
                ]) ?>
            </div>
        </div>
    <?php elseif ($userBlockComment && $userBlockComment->date >= time()) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                <?= Yii::t('frontend', 'views.game.view.blocked-reason', [
                    'date' => FormatHelper::asDatetime($userBlockComment->date),
                    'reason' => $userBlockComment->userBlockReason->text,
                ]) ?>
            </div>
        </div>
    <?php else: ?>
        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'errorOptions' => [
                    'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center notification-error',
                    'tag' => 'div'
                ],
                'options' => ['class' => 'row'],
                'template' =>
                    '<div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center strong">{label}</div>
                </div>
                <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">{input}</div>
                </div>
                <div class="row">{error}</div>',
            ],
        ]) ?>
        <?= $form->field($model, 'text')->textarea() ?>
        <div class="row margin-top-small">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton(Yii::t('frontend', 'views.game.view.submit'), ['class' => 'btn margin']) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    <?php endif ?>
<?php endif ?>
