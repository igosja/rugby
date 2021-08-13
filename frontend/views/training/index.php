<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Player;
use common\models\db\Team;
use common\models\db\Training;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var bool $onBuilding
 * @var Team $team
 * @var View $this
 * @var Training[] $trainingArray
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//team/_team-top-left', ['team' => $team]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong text-size-1">
                <?= Yii::t('frontend', 'views.training.title') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                <?= Yii::t('frontend', 'views.training.level') ?>:
                <span class="strong"><?= $team->baseTraining->level ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                <?= Yii::t('frontend', 'views.training.speed', [
                    'max' => $team->baseTraining->training_speed_max,
                    'min' => $team->baseTraining->training_speed_min,
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                <?= Yii::t('frontend', 'views.training.available.power', [
                    'available' => $team->availableTrainingPower(),
                    'count' => $team->baseTraining->power_count,
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                <?= Yii::t('frontend', 'views.training.available.special', [
                    'available' => $team->availableTrainingSpecial(),
                    'count' => $team->baseTraining->special_count,
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if ($onBuilding) : ?>del<?php endif ?>">
                <?= Yii::t('frontend', 'views.training.available.position', [
                    'available' => $team->availableTrainingPosition(),
                    'count' => $team->baseTraining->position_count,
                ]) ?>
            </div>
        </div>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center <?php if ($onBuilding) : ?>del<?php endif ?>">
        <?= Yii::t('frontend', 'views.training.price.power') ?>
        <span class="strong">
            <?= FormatHelper::asCurrency($team->baseTraining->power_price) ?>
        </span>
        <?= Yii::t('frontend', 'views.training.price.special') ?>
        <span class="strong">
            <?= FormatHelper::asCurrency($team->baseTraining->special_price) ?>
        </span>
        <?= Yii::t('frontend', 'views.training.price.position') ?>
        <span class="strong">
            <?= FormatHelper::asCurrency($team->baseTraining->position_price) ?>
        </span>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Yii::t('frontend', 'views.training.p') ?>
    </div>
</div>
<?php if ($trainingArray) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <?= Yii::t('frontend', 'views.training.index.p') ?>:
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
            <table class="table table-bordered table-hover">
                <tr>
                    <th><?= Yii::t('frontend', 'views.th.player') ?></th>
                    <th class="col-1 hidden-xs"
                        title="<?= Yii::t('frontend', 'views.title.national') ?>"><?= Yii::t('frontend', 'views.th.national') ?></th>
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.title.age') ?>"><?= Yii::t('frontend', 'views.th.age') ?></th>
                    <th class="col-10"
                        title="<?= Yii::t('frontend', 'views.title.nominal-power') ?>"><?= Yii::t('frontend', 'views.th.nominal-power') ?></th>
                    <th class="col-10"
                        title="<?= Yii::t('frontend', 'views.title.position') ?>"><?= Yii::t('frontend', 'views.th.position') ?></th>
                    <th class="col-15 hidden-xs"
                        title="<?= Yii::t('frontend', 'views.title.special') ?>"><?= Yii::t('frontend', 'views.th.special') ?></th>
                    <th class="col-10" title="<?= Yii::t('frontend', 'views.training.index.title.progress') ?>">%</th>
                    <th class="col-1"></th>
                </tr>
                <?php foreach ($trainingArray as $item) : ?>
                    <tr>
                        <td>
                            <?= $item->player->getPlayerLink() ?>
                        </td>
                        <td class="hidden-xs text-center">
                            <?= $item->player->country->getImageLink() ?>
                        </td>
                        <td class="text-center"><?= $item->player->age ?></td>
                        <td class="text-center">
                            <?= $item->player->power_nominal ?>
                            <?php if ($item->is_power) : ?>
                                + 1
                            <?php endif ?>
                        </td>
                        <td class="text-center">
                            <?= $item->player->position() ?>
                            <?php if ($item->position_id) : ?>
                                + <?= $item->position->name ?>
                            <?php endif ?>
                        </td>
                        <td class="text-center">
                            <?= $item->player->special() ?>
                            <?php if ($item->special_id) : ?>
                                + <?= $item->special->name ?>
                            <?php endif ?>
                        </td>
                        <td class="text-center">
                            <?= $item->percent ?>%
                        </td>
                        <td class="text-center">
                            <?= Html::a(
                                '<i class="fa fa-times-circle"></i>',
                                ['cancel', 'id' => $item->id],
                                ['title' => Yii::t('frontend', 'views.training.index.link.cancel')]
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                <tr>
                    <th><?= Yii::t('frontend', 'views.th.player') ?></th>
                    <th class="hidden-xs"
                        title="<?= Yii::t('frontend', 'views.title.national') ?>"><?= Yii::t('frontend', 'views.th.national') ?></th>
                    <th title="<?= Yii::t('frontend', 'views.title.age') ?>"><?= Yii::t('frontend', 'views.th.age') ?></th>
                    <th title="<?= Yii::t('frontend', 'views.title.nominal-power') ?>"><?= Yii::t('frontend', 'views.th.nominal-power') ?></th>
                    <th title="<?= Yii::t('frontend', 'views.title.position') ?>"><?= Yii::t('frontend', 'views.th.position') ?></th>
                    <th class="hidden-xs"
                        title="<?= Yii::t('frontend', 'views.title.special') ?>"><?= Yii::t('frontend', 'views.th.special') ?></th>
                    <th title="<?= Yii::t('frontend', 'views.training.index.title.progress') ?>">%</th>
                    <th></th>
                </tr>
            </table>
        </div>
    </div>
<?php endif ?>
<?= Html::beginForm(['index']) ?>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'attribute' => 'squad',
                'footer' => Yii::t('frontend', 'views.th.player'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.player'),
                'value' => static function (Player $model) {
                    return $model->getPlayerLink();
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
                'attribute' => 'age',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.age'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.age')],
                'label' => Yii::t('frontend', 'views.th.age'),
                'value' => static function (Player $model) {
                    return $model->age;
                }
            ],
            [
                'attribute' => 'power_nominal',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.nominal-power'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.nominal-power')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.title.nominal-power')],
                'label' => Yii::t('frontend', 'views.th.nominal-power'),
                'value' => static function (Player $model) {
                    $result = $model->power_nominal;
                    if ($model->date_no_action < time()) {
                        $result .= ' ' . Html::checkbox('power[' . $model->id . ']');
                    }
                    return $result;
                }
            ],
            [
                'attribute' => 'position',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.position'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-15', 'title' => Yii::t('frontend', 'views.title.position')],
                'label' => Yii::t('frontend', 'views.th.position'),
                'value' => static function (Player $model) {
                    $result = '<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">'
                        . $model->position()
                        . '</div><div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">';
                    if ($model->date_no_action < time()) {
                        $result .= ' ' . $model->trainingPositionDropDownList();
                    }
                    $result .= '</div></div>';
                    return $result;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.special'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.special')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-15', 'title' => Yii::t('frontend', 'views.title.special')],
                'label' => Yii::t('frontend', 'views.th.special'),
                'value' => static function (Player $model) {
                    $result = '<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">'
                        . $model->special()
                        . '</div><div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">';
                    if ($model->date_no_action < time()) {
                        $result .= ' ' . $model->trainingSpecialDropDownList();
                    }
                    $result .= '</div></div>';
                    return $result;
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'rowOptions' => static function (Player $model) {
                if ($model->squad) {
                    return ['style' => ['background-color' => '#' . $model->squad->color]];
                }
                return [];
            },
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
        <?= Html::submitButton(Yii::t('frontend', 'views.training.index.submit'), ['class' => 'btn margin']) ?>
    </div>
</div>
<?= Html::endForm() ?>
<?= $this->render('//site/_show-full-table') ?>
