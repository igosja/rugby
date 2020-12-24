<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Game;
use common\models\db\National;
use common\models\db\Physical;
use common\models\db\Player;
use common\models\db\TournamentType;
use frontend\assets\LineupAsset;
use frontend\models\forms\GameSend;
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
 * @var National $national
 * @var View $this
 */

LineupAsset::register($this);

?>
<?php if ($isVip) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
            <a href="javascript:" class="link-template-save">Сохранить как...</a>
            |
            <a href="javascript:" class="link-template-load">Загрузить шаблон</a>
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
                    <?= Html::submitButton('Сохранить', ['class' => 'btn margin', 'id' => 'template-save-submit']) ?>
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
                'label' => 'Дата',
                'value' => static function (Game $model) {
                    return FormatHelper::asDatetime($model->schedule->date);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'label' => 'Турнир',
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
                'label' => 'Стадия',
                'headerOptions' => ['class' => 'hidden-xs'],
                'value' => static function (Game $model) {
                    return $model->schedule->stage->name;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'value' => static function (Game $model) use ($national) {
                    return $model->home_national_id === $national->id ? 'Д' : 'Г';
                }
            ],
            [
                'attribute' => 'opponent',
                'contentOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'label' => '',
                'value' => static function (Game $model) use ($national) {
                    if ($model->home_national_id === $national->id) {
                        return $model->guestNational->nationalLink();
                    }

                    return $model->homeNational->nationalLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footerOptions' => ['class' => 'hidden-xs'],
                'label' => 'C/C',
                'headerOptions' => [
                    'class' => 'hidden-xs',
                    'title' => 'Соотношение сил (чем больше это число, тем сильнее ваш соперник)',
                ],
                'value' => static function (Game $model) use ($national): string {
                    if ($model->home_national_id === $national->id) {
                        return round($model->guestNational->power_vs / $national->power_vs * 100) . '%';
                    }

                    return round($model->homeNational->power_vs / $national->power_vs * 100) . '%';
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
                'value' => static function (Game $model) use ($national) {
                    return Html::a(
                        $model->home_national_id === $national->id
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
        [<?= Html::a('Зрители', ['visitor/view', 'id' => $game->id], ['target' => '_blank']) ?>]
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
                <td class="col-30 text-center strong">Тактика</td>
                <td class="col-30 text-center strong">Грубость</td>
                <td class="col-30 text-center strong">
                    Стиль
                    <i class="fa fa-question-circle-o"
                       title="силовым лучше всего играть против скоростного;<br/>скоростным лучше всего играть против технического;<br/>техническим лучше всего играть против силового."></i>
                </td>
            </tr>
            <tr>
                <td class="text-right strong">1 звено:</td>
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
                Сила состава: <span class="strong span-power">0</span>
                <br/>
                Оптимальность позиций: <span class="strong span-position-percent">0</span>%
                <br/>
                Оптимальность состава: <span class="strong span-lineup-percent">0</span>%
                <br/>
                Сыгранность:
                <span class="strong span-teamwork">0</span>%
            </div>
        </div>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center strong">
                Игроки:
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
                Капитан:
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
                <?= Html::submitButton('Сохранить', ['class' => 'btn margin']) ?>
                <?= Html::a('Очистить', 'javascript:', ['class' => 'btn margin', 'id' => 'reset-lineup']) ?>
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
                    'footer' => 'Игрок',
                    'format' => 'raw',
                    'label' => 'Игрок',
                    'value' => static function (Player $model) {
                        return $model->getPlayerLink(['target' => '_blank'])
                            . $model->iconInjury()
                            . $model->iconNational();
                    }
                ],
                [
                    'attribute' => 'country',
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => 'Нац',
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Национальность'],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => 'Национальность'],
                    'label' => 'Нац',
                    'value' => static function (Player $model) {
                        return $model->country->getImageLink();
                    }
                ],
                [
                    'attribute' => 'position',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Поз',
                    'footerOptions' => ['title' => 'Позиция'],
                    'format' => 'raw',
                    'headerOptions' => ['title' => 'Позиция'],
                    'label' => 'Поз',
                    'value' => static function (Player $model) {
                        return $model->position();
                    }
                ],
                [
                    'attribute' => 'age',
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => 'В',
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Возраст'],
                    'headerOptions' => ['class' => 'hidden-xs', 'title' => 'Возраст'],
                    'label' => 'В',
                    'value' => static function (Player $model) {
                        return $model->age;
                    }
                ],
                [
                    'attribute' => 'power_nominal',
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => 'С',
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Сила'],
                    'headerOptions' => ['class' => 'hidden-xs', 'title' => 'Сила'],
                    'label' => 'С',
                    'value' => static function (Player $model) {
                        return $model->power_nominal;
                    }
                ],
                [
                    'attribute' => TournamentType::FRIENDLY === $game->schedule->tournament_type_id ? '' : 'tire',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'У',
                    'footerOptions' => ['title' => 'Усталость'],
                    'headerOptions' => ['title' => 'Усталость'],
                    'label' => 'У',
                    'value' => static function (Player $model) use ($game) {
                        return TournamentType::FRIENDLY === $game->schedule->tournament_type_id
                            ? '25'
                            : $model->tire;
                    }
                ],
                [
                    'attribute' => TournamentType::FRIENDLY === $game->schedule->tournament_type_id ? '' : 'physical',
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => 'Ф',
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Форма'],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'hidden-xs', 'title' => 'Форма'],
                    'label' => 'Ф',
                    'value' => static function (Player $model) use ($game) {
                        return TournamentType::FRIENDLY === $game->schedule->tournament_type_id
                            ? (Physical::findOne(Physical::DEFAULT_ID))->image()
                            : $model->physical->image();
                    }
                ],
                [
                    'attribute' => TournamentType::FRIENDLY === $game->schedule->tournament_type_id ? 'power_nominal' : 'power_real',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'РС',
                    'footerOptions' => ['title' => 'Реальная сила'],
                    'headerOptions' => ['title' => 'Реальная сила'],
                    'label' => 'РС',
                    'value' => static function (Player $model) use ($game) {
                        return TournamentType::FRIENDLY === $game->schedule->tournament_type_id
                            ? round($model->power_nominal * 0.75)
                            : $model->power_real;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Спец',
                    'footerOptions' => ['title' => 'Спецвозможности'],
                    'headerOptions' => ['title' => 'Спецвозможности'],
                    'label' => 'Спец',
                    'value' => static function (Player $model) {
                        return $model->special();
                    }
                ],
                [
                    'attribute' => 'style',
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => 'Ст',
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Стиль'],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'hidden-xs', 'title' => 'Стиль'],
                    'label' => 'Ст',
                    'value' => static function (Player $model) {
                        return $model->iconStyle(true);
                    }
                ],
                [
                    'attribute' => 'game_row',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'ИО',
                    'footerOptions' => ['title' => 'Играл/отдыхал подряд'],
                    'headerOptions' => ['title' => 'Играл/отдыхал подряд'],
                    'label' => 'ИО',
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
