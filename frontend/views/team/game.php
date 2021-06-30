<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Game;
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
 * @var array $totalGameResult
 * @var int $totalPoint
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
<?= Html::beginForm(['team/game', 'id' => $team->id], 'get') ?>
<div class="row margin-top-small">
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <?= $this->render('//team/_team-links', ['id' => $team->id]) ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                <?= Html::label(Yii::t('frontend', 'views.label.season'), 'seasonId') ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?= Html::dropDownList(
                    'seasonId',
                    $seasonId,
                    $seasonArray,
                    ['class' => 'form-control submit-on-change', 'id' => 'seasonId']
                ) ?>
            </div>
        </div>
    </div>
</div>
<?= Html::endForm() ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-bordered table-hover">
            <tr>
                <th><?= Yii::t('frontend', 'views.team.game.th.results') ?></th>
                <th class="col-10"
                    title="<?= Yii::t('frontend', 'views.team.game.title.game') ?>"><?= Yii::t('frontend', 'views.team.game.th.game') ?></th>
                <th class="col-10 hidden-xs"
                    title="<?= Yii::t('frontend', 'views.team.game.title.win') ?>"><?= Yii::t('frontend', 'views.team.game.th.win') ?></th>
                <th class="col-10 hidden-xs"
                    title="<?= Yii::t('frontend', 'views.team.game.title.draw') ?>"><?= Yii::t('frontend', 'views.team.game.th.draw') ?></th>
                <th class="col-10 hidden-xs"
                    title="<?= Yii::t('frontend', 'views.team.game.title.loose') ?>"><?= Yii::t('frontend', 'views.team.game.th.loose') ?></th>
            </tr>
            <tr>
                <td><?= Yii::t('frontend', 'views.team.game.td.total') ?></td>
                <td class="text-center"><?= $totalGameResult['game'] ?></td>
                <td class="text-center"><?= $totalGameResult['win'] ?></td>
                <td class="text-center"><?= $totalGameResult['draw'] ?></td>
                <td class="text-center"><?= $totalGameResult['loose'] ?></td>
            </tr>
        </table>
    </div>
</div>
<div class="row margin-top">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.team.game.th.date'),
                'headerOptions' => ['class' => 'col-15'],
                'label' => Yii::t('frontend', 'views.team.game.th.date'),
                'value' => static function (Game $model): string {
                    return FormatHelper::asDate($model->schedule->date);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.team.game.th.tournament'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'col-30 hidden-xs'],
                'label' => Yii::t('frontend', 'views.team.game.th.tournament'),
                'value' => static function (Game $model): string {
                    return $model->schedule->tournamentType->name;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.team.game.th.stage'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'col-10 hidden-xs'],
                'label' => Yii::t('frontend', 'views.team.game.th.stage'),
                'value' => static function (Game $model): string {
                    return $model->schedule->stage->name;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.team.game.title.home-guest')],
                'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => Yii::t('frontend', 'views.team.game.title.home-guest')],
                'value' => static function (Game $model) use ($team): string {
                    return $model->gameHomeGuest($team);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.team.game.th.power-percent'),
                'footerOptions' => [
                    'class' => 'hidden-xs',
                    'title' => Yii::t('frontend', 'views.team.game.title.power-percent')
                ],
                'headerOptions' => [
                    'class' => 'col-5 hidden-xs',
                    'title' => Yii::t('frontend', 'views.team.game.title.power-percent')
                ],
                'label' => Yii::t('frontend', 'views.team.game.th.power-percent'),
                'value' => static function (Game $model) use ($team): string {
                    return $model->gamePowerPercent($team);
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.team.game.th.opponent'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.team.game.th.opponent'),
                'value' => static function (Game $model) use ($team): string {
                    return $model->opponentLink($team);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.team.game.th.auto'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.team.game.title.auto')],
                'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => Yii::t('frontend', 'views.team.game.title.auto')],
                'label' => Yii::t('frontend', 'views.team.game.th.auto'),
                'value' => static function (Game $model) use ($team): string {
                    return $model->gameAuto($team);
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.team.game.th.score'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.team.game.th.score'),
                'value' => static function (Game $model) use ($team): string {
                    return Html::a(
                        $model->formatTeamScore($team),
                        ['game/view', 'id' => $model->id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => $totalPoint,
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.team.game.title.plus-minus')],
                'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => Yii::t('frontend', 'views.team.game.title.plus-minus')],
                'value' => static function (Game $model) use ($team): string {
                    return $model->gamePlusMinus($team);
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
        <?= $this->render('//team/_team-links', ['id' => $team->id]) ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table') ?>
