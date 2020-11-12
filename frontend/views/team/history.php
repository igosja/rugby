<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\History;
use common\models\db\Team;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var array $seasonArray
 * @var int $seasonId
 * @var Team $team
 * @var View $this
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//team/_team-top-left', ['team' => $team]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <?= $this->render('//team/_team-top-right', ['team' => $team]) ?>
    </div>
</div>
<?= Html::beginForm(['team/event', 'id' => $team->team_id], 'get') ?>
<div class="row margin-top-small">
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <?= $this->render('//team/_team-links', ['id' => $team->team_id]) ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                <?= Html::label('Сезон', 'seasonId') ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?= Html::dropDownList(
                    'seasonId',
                    $seasonId,
                    $seasonArray,
                    ['class' => 'form-control submit-on-change', 'id' => 'season_id']
                ) ?>
            </div>
        </div>
    </div>
</div>
<?= Html::endForm() ?>
<div class="row">
    <?php

// TODO refactor

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Дата',
                'headerOptions' => ['class' => 'col-15'],
                'label' => 'Дата',
                'value' => static function (History $model) {
                    return FormatHelper::asDate($model->history_date);
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
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//team/_team-links', ['id' => $team->team_id]) ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table') ?>
