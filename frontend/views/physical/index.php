<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Player;
use common\models\db\Schedule;
use common\models\db\Team;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var int $countSchedule
 * @var ActiveDataProvider $dataProvider
 * @var bool $onBuilding
 * @var array $opponentArray
 * @var array $playerPhysicalArray
 * @var Schedule[] $scheduleArray
 * @var Team $team
 * @var View $this
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//team/_team-top-left', ['team' => $team]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong text-size-1">
                Центр физподготовки
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                Уровень:
                <span class="strong"><?= $team->basePhysical->level ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                Бонус к изменению усталости:
                <span class="strong"><?= $team->basePhysical->tire_bonus ?>%</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                Осталось изменений формы:
                <span class="strong" id="physical-available" data-url="<?= Url::to(['physical/change']) ?>">
                    <?= $team->availablePhysical() ?>
                </span>
                из
                <span class="strong"><?= $team->basePhysical->change_count ?></span>
            </div>
        </div>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        Здесь - <span class="strong">в физцентре</span> -
        вы можете поменять физическую форму для игроков своей команды:
    </div>
</div>
<div class="row">
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
                    return $model->getPlayerLink();
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
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'В',
                'footerOptions' => ['title' => 'Возраст'],
                'headerOptions' => ['title' => 'Возраст'],
                'label' => 'В',
                'value' => static function (Player $model) {
                    return $model->age;
                }
            ],
            [
                'attribute' => 'power',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'С',
                'footerOptions' => ['title' => 'Номинальная сила'],
                'headerOptions' => ['title' => 'Номинальная сила'],
                'label' => 'С',
                'value' => static function (Player $model) {
                    return $model->power_nominal;
                }
            ],
        ];

        for ($i = 0; $i < $countSchedule; $i++) {
            $columns[] = [
                'contentOptions' => static function (Player $model) use ($i, $playerPhysicalArray) {
                    return [
                        'class' => 'text-center ' . $playerPhysicalArray[$model->id][$i]['class'],
                        'data' => [
                            'physical' => $playerPhysicalArray[$model->id][$i]['physical_id'],
                            'player' => $playerPhysicalArray[$model->id][$i]['player_id'],
                            'schedule' => $playerPhysicalArray[$model->id][$i]['schedule_id'],
                        ],
                        'id' => $playerPhysicalArray[$model->id][$i]['id'],
                    ];
                },
                'footer' => Html::img(
                    [
                        'physical/image',
                        'team' => $opponentArray[$i],
                    ],
                    [
                        'alt' => $opponentArray[$i],
                        'title' => $opponentArray[$i],
                    ]
                ),
                'format' => 'raw',
                'header' => Html::img(
                    [
                        'physical/image',
                        'stage' => $scheduleArray[$i]->stage->name,
                        'tournament' => $scheduleArray[$i]->tournamentType->name,
                    ],
                    [
                        'alt' => $scheduleArray[$i]->tournamentType->name . ' ' . $scheduleArray[$i]->stage->name,
                        'title' => $scheduleArray[$i]->tournamentType->name . ' ' . $scheduleArray[$i]->stage->name,
                    ]
                ),
                'value' => static function (Player $model) use ($playerPhysicalArray, $i) {
                    return Html::img(
                        '/img/physical/' . $playerPhysicalArray[$model->id][$i]['physical_id'] . '.png',
                        [
                            'alt' => $playerPhysicalArray[$model->id][$i]['physical_name'],
                            'title' => $playerPhysicalArray[$model->id][$i]['physical_name'],
                        ]
                    );
                }
            ];
        }
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'showFooter' => true,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::a(
            'Удалить все изменения',
            ['clear'],
            ['class' => 'btn margin']
        ) ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table') ?>
