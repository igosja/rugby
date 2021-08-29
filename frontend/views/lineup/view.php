<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Game;
use common\models\db\Physical;
use common\models\db\Player;
use common\models\db\Team;
use common\models\db\TournamentType;
use frontend\assets\LineupAsset;
use frontend\models\forms\GameSend;
use rmrevin\yii\fontawesome\FAR;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/**
 * @var ActiveDataProvider $gameDataProvider
 * @var Game $game
 * @var bool $isVip
 * @var GameSend $model
 * @var array $moodArray
 * @var int $player_1_id
 * @var int $player_2_id
 * @var int $player_3_id
 * @var int $player_4_id
 * @var int $player_5_id
 * @var int $player_6_id
 * @var int $player_7_id
 * @var int $player_8_id
 * @var int $player_9_id
 * @var int $player_10_id
 * @var int $player_11_id
 * @var int $player_12_id
 * @var int $player_13_id
 * @var int $player_14_id
 * @var int $player_15_id
 * @var Player[] $player1array
 * @var Player[] $player2array
 * @var Player[] $player3array
 * @var Player[] $player4array
 * @var Player[] $player5array
 * @var Player[] $player6array
 * @var Player[] $player7array
 * @var Player[] $player8array
 * @var Player[] $player9array
 * @var Player[] $player10array
 * @var Player[] $player11array
 * @var Player[] $player12array
 * @var Player[] $player13array
 * @var Player[] $player14array
 * @var Player[] $player15array
 * @var ActiveDataProvider $playerDataProvider
 * @var array $rudenessArray
 * @var array $styleArray
 * @var array $tacticArray
 * @var Team $team
 * @var View $this
 */

LineupAsset::register($this);

?>
<?php if ($isVip) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
            <a href="javascript:"
               class="link-template-save"><?= Yii::t('frontend', 'views.lineup.view.link.save') ?></a>
            |
            <a href="javascript:"
               class="link-template-load"><?= Yii::t('frontend', 'views.lineup.view.link.load') ?></a>
        </div>
    </div>
    <div class="row margin-top-small div-template-save" style="display: none;">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php $form = ActiveForm::begin([
                'action' => ['lineup/template-save'],
                'fieldConfig' => [
                    'options' => ['class' => 'row'],
                    'template' =>
                        '<div class="col-lg-5 col-md-4 col-sm-4 col-xs-12 text-right xs-text-center">{label}</div>
                    <div class="col-lg-3 col-md-5 col-sm-5 col-xs-12">{input}</div>',
                ],
                'id' => 'template-save',
                'options' => ['class' => 'margin-no'],
            ]) ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?= $form->field($model, 'name')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <?= Html::submitButton(Yii::t('frontend', 'views.lineup.view.submit.template'), ['class' => 'btn margin', 'id' => 'template-save-submit']) ?>
                </div>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
    <div
            class="row margin-top div-template-load"
            data-url="<?= Url::to(['lineup/template']) ?>"
            style="display: none;"
    >
    </div>
<?php endif; ?>
<div class="row margin-top">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'label' => Yii::t('frontend', 'views.th.date'),
                'value' => static function (Game $model) {
                    return FormatHelper::asDatetime($model->schedule->date);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.lineup.view.th.tournament'),
                'headerOptions' => ['class' => 'hidden-xs'],
                'value' => static function (Game $model) {
                    return Html::a(
                        $model->schedule->tournamentType->name,
                        ['lineup/view', 'id' => $model->id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footerOptions' => ['class' => 'hidden-xs'],
                'label' => Yii::t('frontend', 'views.lineup.view.th.stage'),
                'headerOptions' => ['class' => 'hidden-xs'],
                'value' => static function (Game $model) {
                    return $model->schedule->stage->name;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'value' => static function (Game $model) use ($team) {
                    return $model->home_team_id === $team->id ? Yii::t('frontend', 'views.home') : Yii::t('frontend', 'views.guest');
                }
            ],
            [
                'attribute' => 'opponent',
                'contentOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'label' => '',
                'value' => static function (Game $model) use ($team) {
                    if ($model->home_team_id === $team->id) {
                        return $model->guestTeam->getTeamLink();
                    }

                    return $model->homeTeam->getTeamLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footerOptions' => ['class' => 'hidden-xs'],
                'label' => Yii::t('frontend', 'views.lineup.view.th.ratio'),
                'headerOptions' => [
                    'class' => 'hidden-xs',
                    'title' => Yii::t('frontend', 'views.lineup.view.title.ratio'),
                ],
                'value' => static function (Game $model) use ($team): string {
                    if ($model->home_team_id === $team->id) {
                        return round($model->guestTeam->power_vs / $team->power_vs * 100) . '%';
                    }

                    return round($model->homeTeam->power_vs / $team->power_vs * 100) . '%';
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'label' => '',
                'value' => static function (Game $model) {
                    return Html::a(
                        '?',
                        ['game/preview', 'id' => $model->id],
                        ['target' => '_blank']
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'label' => '',
                'value' => static function (Game $model) use ($team) {
                    return Html::a(
                        $model->home_team_id === $team->id
                            ? ($model->home_tactic_id ? '+' : '-')
                            : ($model->guest_tactic_id ? '+' : '-'),
                        ['lineup/view', 'id' => $model->id]
                    );
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $gameDataProvider,
            'rowOptions' => static function (Game $model) use ($game) {
                if ($model->id === $game->id) {
                    return ['class' => 'info'];
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
<?= $this->render('//site/_show-full-table') ?>
<?php $form = ActiveForm::begin([
    'id' => 'lineup-send',
    'options' => ['class' => 'margin-no game-form', 'data-url' => Url::to(['lineup/teamwork', 'id' => $game->id])],
]) ?>
<div class="row margin-top">
    <?= $form
        ->field($model, 'ticket', [
            'template' => '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 text-right strong">
                        {label}
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-2 col-xs-4 text-center">
                        {input}
                        </div>'
        ])
        ->textInput(['class' => 'form-control', 'disabled' => !$model->home]) ?>
    <div class="col-lg-1 col-md-1 col-sm-2 col-xs-4" style="height: 20px">
        [<?= Html::a(Yii::t('frontend', 'views.lineup.view.link.visitor'), ['visitor/view', 'id' => $game->id], ['target' => '_blank']) ?>
        ]
    </div>
    <?= $form
        ->field($model, 'mood', [
            'template' => ' <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 text-right strong">
                            {label}
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-8">
                            {input}
                            </div>'
        ])
        ->dropDownList($moodArray, ['class' => 'form-control']) ?>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table">
            <tr>
                <td></td>
                <td class="col-30 text-center strong"><?= Yii::t('frontend', 'views.lineup.view.tactic') ?></td>
                <td class="col-30 text-center strong"><?= Yii::t('frontend', 'views.lineup.view.rudeness') ?></td>
                <td class="col-30 text-center strong">
                    <?= Yii::t('frontend', 'views.lineup.view.style') ?>
                    <?= FAR::icon(FontAwesome::_QUESTION_CIRCLE, ['title' => Yii::t('frontend', 'views.lineup.view.tooltip.style')]) ?>
                </td>
            </tr>
            <tr>
                <td class="text-right strong"></td>
                <td>
                    <?= $form->field($model, 'tactic')
                        ->dropDownList($tacticArray, ['class' => 'form-control'])
                        ->label(false) ?>
                </td>
                <td>
                    <?= $form->field($model, 'rudeness')
                        ->dropDownList($rudenessArray, ['class' => 'form-control'])
                        ->label(false) ?>
                </td>
                <td>
                    <?= $form->field($model, 'style')
                        ->dropDownList($styleArray, ['class' => 'form-control'])
                        ->label(false) ?>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert info">
                <?= Yii::t('frontend', 'views.lineup.view.power') ?>: <span class="strong span-power">0</span>
                <br/>
                <?= Yii::t('frontend', 'views.lineup.view.optimality.1') ?>: <span class="strong span-position-percent">0</span>%
                <br/>
                <?= Yii::t('frontend', 'views.lineup.view.optimality.2') ?>: <span
                        class="strong span-lineup-percent">0</span>%
                <br/>
                <?= Yii::t('frontend', 'views.lineup.view.teamwork') ?>:
                <span class="strong span-teamwork">0</span>%
            </div>
        </div>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center strong">
                <?= Yii::t('frontend', 'views.lineup.view.player') ?>:
            </div>
        </div>
        <?php for ($i = 1; $i <= 15; $i++): ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?= $form
                        ->field($model, 'line[' . $i . ']')
                        ->dropDownList([], [
                            'class' => 'form-control lineup-change player-change',
                            'data' => [
                                'position' => $i,
                            ],
                            'id' => 'line-' . $i,
                        ])
                        ->label(false) ?>
                </div>
            </div>
        <?php endfor ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center strong">
                <?= Yii::t('frontend', 'views.lineup.view.captain') ?>:
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= $form
                    ->field($model, 'captain')
                    ->dropDownList([], [
                        'class' => 'form-control',
                        'data' => [
                            'id' => $model->captain,
                        ],
                        'id' => 'captain',
                    ])
                    ->label(false) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton(Yii::t('frontend', 'views.lineup.view.submit'), ['class' => 'btn margin']) ?>
                <?= Html::a(Yii::t('frontend', 'views.lineup.view.reset'), 'javascript:', ['class' => 'btn margin', 'id' => 'reset-lineup']) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 table-responsive">
        <?php

        try {
            $columns = [
                [
                    'attribute' => 'squad',
                    'contentOptions' => static function (Player $model) {
                        if ($model->squad) {
                            return ['style' => ['background-color' => '#' . $model->squad->color]];
                        }
                        return [];
                    },
                    'footer' => Yii::t('frontend', 'views.th.player'),
                    'format' => 'raw',
                    'label' => Yii::t('frontend', 'views.th.player'),
                    'value' => static function (Player $model) {
                        return $model->getPlayerLink(['target' => '_blank'])
                            . $model->iconInjury()
                            . $model->iconNational();
                    }
                ],
                [
                    'attribute' => 'country',
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => Yii::t('frontend', 'views.th.national'),
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.national')],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => Yii::t('frontend', 'views.title.national')],
                    'label' => Yii::t('frontend', 'views.th.national'),
                    'value' => static function (Player $model) {
                        return $model->country->getImageLink();
                    }
                ],
                [
                    'attribute' => 'position',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.th.position'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.title.national')],
                    'format' => 'raw',
                    'headerOptions' => ['title' => Yii::t('frontend', 'views.title.national')],
                    'label' => Yii::t('frontend', 'views.th.national'),
                    'value' => static function (Player $model) {
                        return $model->position();
                    }
                ],
                [
                    'attribute' => 'age',
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => Yii::t('frontend', 'views.th.age'),
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.age')],
                    'headerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.age')],
                    'label' => Yii::t('frontend', 'views.th.age'),
                    'value' => static function (Player $model) {
                        return $model->age;
                    }
                ],
                [
                    'attribute' => 'power_nominal',
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => Yii::t('frontend', 'views.th.power'),
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.power')],
                    'headerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.power')],
                    'label' => Yii::t('frontend', 'views.th.power'),
                    'value' => static function (Player $model) {
                        return $model->power_nominal;
                    }
                ],
                [
                    'attribute' => TournamentType::FRIENDLY === $game->schedule->tournament_type_id ? '' : 'tire',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.th.tire'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.title.tire')],
                    'headerOptions' => ['title' => Yii::t('frontend', 'views.title.tire')],
                    'label' => Yii::t('frontend', 'views.th.tire'),
                    'value' => static function (Player $model) use ($game) {
                        return TournamentType::FRIENDLY === $game->schedule->tournament_type_id
                            ? '25'
                            : $model->tire;
                    }
                ],
                [
                    'attribute' => TournamentType::FRIENDLY === $game->schedule->tournament_type_id ? '' : 'physical',
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => Yii::t('frontend', 'views.th.physical'),
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.physical')],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.physical')],
                    'label' => Yii::t('frontend', 'views.th.physical'),
                    'value' => static function (Player $model) use ($game) {
                        return TournamentType::FRIENDLY === $game->schedule->tournament_type_id
                            ? (Physical::findOne(Physical::DEFAULT_ID))->image()
                            : $model->physical->image();
                    }
                ],
                [
                    'attribute' => TournamentType::FRIENDLY === $game->schedule->tournament_type_id ? 'power_nominal' : 'power_real',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.th.real-power'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.title.real-power')],
                    'headerOptions' => ['title' => Yii::t('frontend', 'views.title.real-power')],
                    'label' => Yii::t('frontend', 'views.th.real-power'),
                    'value' => static function (Player $model) use ($game) {
                        return TournamentType::FRIENDLY === $game->schedule->tournament_type_id
                            ? round($model->power_nominal * 0.75)
                            : $model->power_real;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.th.special'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.title.special')],
                    'format' => 'raw',
                    'headerOptions' => ['title' => Yii::t('frontend', 'views.title.special')],
                    'label' => Yii::t('frontend', 'views.th.special'),
                    'value' => static function (Player $model) {
                        return $model->special();
                    }
                ],
                [
                    'attribute' => 'style',
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => Yii::t('frontend', 'views.th.style'),
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.style')],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.style')],
                    'label' => Yii::t('frontend', 'views.th.style'),
                    'value' => static function (Player $model) {
                        return $model->iconStyle(true);
                    }
                ],
                [
                    'attribute' => 'game_row',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.th.row'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.title.row')],
                    'headerOptions' => ['title' => Yii::t('frontend', 'views.title.row')],
                    'label' => Yii::t('frontend', 'views.th.row'),
                    'value' => static function (Player $model) {
                        return $model->game_row;
                    }
                ],
            ];
            Pjax::begin();
            print GridView::widget([
                'columns' => $columns,
                'dataProvider' => $playerDataProvider,
                'rowOptions' => static function (Player $model) {
                    return [
                        'class' => 'tr-player',
                        'id' => 'tr-' . $model->id,
                    ];
                },
                'showFooter' => true,
                'summary' => false,
            ]);
            Pjax::end();
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table') ?>
<?php ActiveForm::end() ?>
<?php
$data = [
    ['array' => 'player_1_array', 'fArray' => $player1array],
    ['array' => 'player_2_array', 'fArray' => $player2array],
    ['array' => 'player_3_array', 'fArray' => $player3array],
    ['array' => 'player_4_array', 'fArray' => $player4array],
    ['array' => 'player_5_array', 'fArray' => $player5array],
    ['array' => 'player_6_array', 'fArray' => $player6array],
    ['array' => 'player_7_array', 'fArray' => $player7array],
    ['array' => 'player_8_array', 'fArray' => $player8array],
    ['array' => 'player_9_array', 'fArray' => $player9array],
    ['array' => 'player_10_array', 'fArray' => $player10array],
    ['array' => 'player_11_array', 'fArray' => $player11array],
    ['array' => 'player_12_array', 'fArray' => $player12array],
    ['array' => 'player_13_array', 'fArray' => $player13array],
    ['array' => 'player_14_array', 'fArray' => $player14array],
    ['array' => 'player_15_array', 'fArray' => $player15array],
];
$scriptBody = '';
foreach ($data as $datum) {
    $array = $datum['array'];
    /**
     * @var Player[] $fArray
     */
    $fArray = $datum['fArray'];

    $scriptBody .= '
        var ' . $array . ' =
        [
        ';
    foreach ($fArray as $item) {
        $scriptBody .= '[
            ' . $item->id . ',
            \'' . $item->position() . ' - ' . $item->power_real . ' - ' . $item->playerName() . '\',
            \'#' . (isset($item->squad) ? $item->squad->color : '') . '\'
            ],';
    }
    $scriptBody .= '];';
}
$scriptBody .= '
    var player_1_id = ' . $player_1_id . ';
    var player_2_id = ' . $player_2_id . ';
    var player_3_id = ' . $player_3_id . ';
    var player_4_id = ' . $player_4_id . ';
    var player_5_id = ' . $player_5_id . ';
    var player_6_id = ' . $player_6_id . ';
    var player_7_id = ' . $player_7_id . ';
    var player_8_id = ' . $player_8_id . ';
    var player_9_id = ' . $player_9_id . ';
    var player_10_id = ' . $player_10_id . ';
    var player_11_id = ' . $player_11_id . ';
    var player_12_id = ' . $player_12_id . ';
    var player_13_id = ' . $player_13_id . ';
    var player_14_id = ' . $player_14_id . ';
    var player_15_id = ' . $player_15_id . ';';
$script = <<< JS
    $scriptBody;
    $(document).on("ready pjax:end", function() {
        player_change();
    })
JS;
$this->registerJs($script, View::POS_END);
?>
