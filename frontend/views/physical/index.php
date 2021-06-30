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
                <?= Yii::t('frontend', 'views.physical.index.physical') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                <?= Yii::t('frontend', 'views.physical.index.level') ?>:
                <span class="strong"><?= $team->basePhysical->level ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                <?= Yii::t('frontend', 'views.physical.index.bonus') ?>:
                <span class="strong"><?= $team->basePhysical->tire_bonus ?>%</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                <?= Yii::t('frontend', 'views.physical.index.available') ?>:
                <span class="strong" id="physical-available" data-url="<?= Url::to(['physical/change']) ?>">
                    <?= $team->availablePhysical() ?>
                </span>
                <?= Yii::t('frontend', 'views.physical.index.from') ?>
                <span class="strong"><?= $team->basePhysical->change_count ?></span>
            </div>
        </div>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Yii::t('frontend', 'views.physical.index.p') ?>:
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
                'footer' => Yii::t('frontend', 'views.th.player'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.player'),
                'value' => static function (Player $model) {
                    return $model->getPlayerLink();
                }
            ],
            [
                'attribute' => 'position',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.position'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                'format' => 'raw',
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                'label' => Yii::t('frontend', 'views.th.position'),
                'value' => static function (Player $model) {
                    return $model->position();
                }
            ],
            [
                'attribute' => 'age',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.age'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'label' => Yii::t('frontend', 'views.th.age'),
                'value' => static function (Player $model) {
                    return $model->age;
                }
            ],
            [
                'attribute' => 'power',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.nominal-power'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.nominal-power')],
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.nominal-power')],
                'label' => Yii::t('frontend', 'views.th.nominal-power'),
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
            Yii::t('frontend', 'views.physical.index.delete'),
            ['clear'],
            ['class' => 'btn margin']
        ) ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table') ?>
