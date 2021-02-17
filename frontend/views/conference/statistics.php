<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\StatisticPlayer;
use common\models\db\StatisticTeam;
use common\models\db\StatisticType;
use common\models\db\Team;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;

/**
 * @var ActiveDataProvider $dataProvider
 * @var Team $myTeam
 * @var int $seasonId
 * @var StatisticType $statisticType
 * @var array $statisticTypeArray
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1><?= Yii::t('frontend', 'views.conference.statistics.h1') ?></h1>
    </div>
</div>
<?= Html::beginForm(null, 'get') ?>
<?= Html::hiddenInput('seasonId', $seasonId) ?>
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
        <?= Html::label(Yii::t('frontend', 'views.conference.statistics.label.statistics'), 'statisticType') ?>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-6 col-xs-8">
        <?= Html::dropDownList(
            'id',
            Yii::$app->request->get('id'),
            $statisticTypeArray,
            ['class' => 'form-control submit-on-change', 'id' => 'statisticType']
        ) ?>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-4"></div>
</div>
<?php

if (1 === $statisticType->statistic_chapter_id) {
    $columns = [
        [
            'class' => SerialColumn::class,
            'contentOptions' => ['class' => 'text-center'],
            'footer' => '#',
            'header' => '#',
            'headerOptions' => ['class' => 'col-10'],
        ],
        [
            'footer' => Yii::t('frontend', 'views.conference.statistics.th.team'),
            'format' => 'raw',
            'label' => Yii::t('frontend', 'views.conference.statistics.th.team'),
            'value' => static function (StatisticTeam $model) {
                return $model->team->getTeamLink();
            }
        ],
        [
            'contentOptions' => ['class' => 'text-center'],
            'headerOptions' => ['class' => 'col-10'],
            'value' => static function (StatisticTeam $model) use ($statisticType) {
                $select = $statisticType->select_field;
                return $model->$select;
            }
        ],
    ];
} else {
    $columns = [
        [
            'class' => SerialColumn::class,
            'contentOptions' => ['class' => 'text-center'],
            'footer' => '#',
            'header' => '#',
            'headerOptions' => ['class' => 'col-10'],
        ],
        [
            'footer' => Yii::t('frontend', 'views.conference.statistics.th.player'),
            'format' => 'raw',
            'label' => Yii::t('frontend', 'views.conference.statistics.th.player'),
            'value' => static function (StatisticPlayer $model) {
                return $model->player->getPlayerLink();
            }
        ],
        [
            'footer' => Yii::t('frontend', 'views.conference.statistics.th.team'),
            'format' => 'raw',
            'label' => Yii::t('frontend', 'views.conference.statistics.th.team'),
            'value' => static function (StatisticPlayer $model) {
                return $model->team->getTeamLink();
            }
        ],
        [
            'contentOptions' => ['class' => 'text-center'],
            'value' => static function (StatisticPlayer $model) use ($statisticType) {
                $select = $statisticType->select_field;
                return $model->$select;
            }
        ],
    ];
}

?>
<div class="row">
    <?php

    try {
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'rowOptions' => static function ($model) use ($myTeam): array {
                if (!$myTeam) {
                    return [];
                }
                $class = '';
                if ($model->team_id === $myTeam->id) {
                    $class = 'info';
                }
                return ['class' => $class];
            },
            'showFooter' => true,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<?= $this->render('//site/_show-full-table') ?>
