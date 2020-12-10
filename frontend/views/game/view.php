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
                '<i class="fa fa-thumbs-up" aria-hidden="true"></i>',
                ['vote', 'id' => $game->id, 'vote' => 1],
                ['title' => 'Интересный и правильный матч, заслуживает внимания']
            ) ?>
        <?php endif ?>
        <span title="Оценка матча"><?= $game->rating() ?></span>
        <?php if (!Yii::$app->user->isGuest) : ?>
            <?= Html::a(
                '<i class="fa fa-thumbs-down" aria-hidden="true"></i>',
                ['vote', 'id' => $game->id, 'vote' => -1],
                ['title' => 'Неинтересный и нелогичный матч, генератор не прав']
            ) ?>
        <?php endif ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
        <span class="strong">Результат</span>
        |
        <?= Html::a(
            'Перед матчем',
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
                <i class="fa fa-plus-square font-green" title="Набрано балов по результатам матча"></i>
            <?php else: ?>
                <i class="fa fa-minus-square font-red" title="Потеряно балов по результатам матча"></i>
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
                Зрителей: <?= $game->visitor ?>.
                Билет: <?= FormatHelper::asCurrency($game->ticket_price) ?>
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
                <i class="fa fa-plus-square font-green" title="Набрано балов по результатам матча"></i>
            <?php else: ?>
                <i class="fa fa-minus-square font-red" title="Потеряно балов по результатам матча"></i>
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
                <td class="text-center">Тактика</td>
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
                <td class="text-center">Стиль</td>
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
                <td class="text-center">Грубость</td>
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
                <td class="text-center">Настрой</td>
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
                <td class="text-center">Соотношение сил</td>
                <td class="text-center">
                    <?= $game->guest_power_percent ?>%
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <span title="Расстановка сил по позициям"><?= $game->home_optimality_1 ?>%</span> |
                    <span title="Соотношение силы состава к ретингу команды"><?= $game->home_optimality_2 ?>%</span>
                </td>
                <td class="text-center">Оптимальность</td>
                <td class="text-center">
                    <span title="Расстановка сил по позициям"><?= $game->guest_optimality_1 ?>%</span> |
                    <span title="Соотношение силы состава к ретингу команды"><?= $game->guest_optimality_2 ?>%</span>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_teamwork ?>%
                </td>
                <td class="text-center">Сыгранность</td>
                <td class="text-center">
                    <?= $game->guest_teamwork ?>%
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_carry ?>
                </td>
                <td class="text-center">Carries</td>
                <td class="text-center">
                    <?= $game->guest_carry ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_clean_break ?>
                </td>
                <td class="text-center">Clean breaks</td>
                <td class="text-center">
                    <?= $game->guest_clean_break ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_defender_beaten ?>
                </td>
                <td class="text-center">Defenders beaten</td>
                <td class="text-center">
                    <?= $game->guest_defender_beaten ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_metre_gained ?>
                </td>
                <td class="text-center">Metres gained</td>
                <td class="text-center">
                    <?= $game->guest_metre_gained ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_pass ?>
                </td>
                <td class="text-center">Passes</td>
                <td class="text-center">
                    <?= $game->guest_pass ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_penalty_conceded ?>
                </td>
                <td class="text-center">Penalty conceded</td>
                <td class="text-center">
                    <?= $game->guest_penalty_conceded ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_turnover_won ?>
                </td>
                <td class="text-center">Turnover won</td>
                <td class="text-center">
                    <?= $game->guest_turnover_won ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_try ?>
                </td>
                <td class="text-center">Tries</td>
                <td class="text-center">
                    <?= $game->guest_try ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_conversion ?>
                </td>
                <td class="text-center">Conversions</td>
                <td class="text-center">
                    <?= $game->guest_conversion ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_penalty_kick ?>
                </td>
                <td class="text-center">Penalty kicks</td>
                <td class="text-center">
                    <?= $game->guest_penalty_kick ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_drop_goal ?>
                </td>
                <td class="text-center">Drop goals</td>
                <td class="text-center">
                    <?= $game->guest_drop_goal ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_red_card ?>
                </td>
                <td class="text-center">Red cards</td>
                <td class="text-center">
                    <?= $game->guest_red_card ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_yellow_card ?>
                </td>
                <td class="text-center">Yellow cards</td>
                <td class="text-center">
                    <?= $game->guest_yellow_card ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_red_card ?>
                </td>
                <td class="text-center">Red cards</td>
                <td class="text-center">
                    <?= $game->guest_red_card ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_forecast ?>
                </td>
                <td class="text-center">Прогноз на матч</td>
                <td class="text-center">
                    <?= $game->guest_forecast ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_power ?>
                </td>
                <td class="text-center">Сила состава</td>
                <td class="text-center">
                    <?= $game->guest_power ?>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <?= $game->home_possession ?>%
                </td>
                <td class="text-center">Possession</td>
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
                    <th class="col-6" title="Позиция">П</th>
                    <th>
                        <?= $game->teamOrNationalLink($side, false) ?>
                    </th>
                    <th class="col-6 hidden-xs" title="Возраст">В</th>
                    <th class="col-6 hidden-xs" title="Номинальная сила">НС</th>
                    <th class="col-6" title="Реальная сила">РС</th>
                    <th class="hidden-xs hidden-sm" title="Спецвозможности">Сп</th>
                    <th class="col-6" title="Tries">T</th>
                    <th class="col-6" title="Conversions">C</th>
                    <th class="col-6" title="Penalty kicks">P</th>
                    <th class="col-6 hidden-xs" title="Drop goals">D</th>
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
                'header' => 'Время',
                'value' => static function (Event $model) {
                    return $model->minute . "'";
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'header' => 'Команда',
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
                'header' => 'Тип',
                'headerOptions' => ['class' => 'text-center hidden-xs'],
                'value' => static function (Event $model) {
                    return $model->icon();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center hidden-xs'],
                'format' => 'raw',
                'header' => 'Событие',
                'headerOptions' => ['class' => 'hidden-xs'],
                'value' => static function (Event $model) {
                    return $model->eventText->text . ' - ' . $model->player->getPlayerLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'header' => 'Счёт',
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
            <span class="strong">Последние комментарии:</span>
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
                Вам заблокирован доступ к комментированию матчей
                <br/>
                Причина - ваш почтовый адрес не подтверждён
            </div>
        </div>
    <?php elseif ($userBlockGame && $userBlockGame->date >= time()) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                Вам заблокирован доступ к комментированию матчей до
                <?= FormatHelper::asDatetime($userBlockGame->date) ?>
                <br/>
                Причина - <?= $userBlockGame->userBlockReason->text ?>
            </div>
        </div>
    <?php elseif ($userBlockComment && $userBlockComment->date >= time()) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                Вам заблокирован доступ к комментированию матчей до
                <?= FormatHelper::asDateTime($userBlockComment->date) ?>
                <br/>
                Причина - <?= $userBlockComment->userBlockReason->text ?>
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
                <?= Html::submitButton('Комментировать', ['class' => 'btn margin']) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    <?php endif ?>
<?php endif ?>
