<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Attitude;
use common\models\db\National;
use common\models\db\Player;
use frontend\controllers\AbstractController;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var AbstractController $controller
 * @var ActiveDataProvider $dataProvider
 * @var National $national
 * @var array $notificationArray
 * @var View $this
 */

$controller = Yii::$app->controller;

$attitudeArray = Attitude::find()
    ->orderBy(['order' => SORT_ASC])
    ->all();
$attitudeArray = ArrayHelper::map($attitudeArray, 'id', 'name');

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//national/_national-top-left', ['national' => $national]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <?= $this->render('//national/_national-top-right', ['national' => $national]) ?>
    </div>
</div>
<?php if ($national->user_id && $controller->myTeam && $controller->myTeam->stadium->city->country->federation->id === $national->federation_id && $national->user_id !== $controller->user->id) : ?>
    <?php $form = ActiveForm::begin([
        'action' => ['attitude-national', 'id' => $national->id],
        'fieldConfig' => [
            'labelOptions' => ['class' => 'strong'],
            'options' => ['class' => 'row text-left'],
            'template' => '<div class="col-lg-3 col-md-3 col-sm-2"></div>{input}',
        ],
    ]) ?>
    <div class="row text-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 relation-head">
            <?= Yii::t('frontend', 'views.national.view.attitude') ?>
            <a href="javascript:" id="relation-link">
                <?= $controller->myTeam->nationalAttitude->name ?>
            </a>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 relation-body hidden">
            <?= $form
                ->field($controller->myTeam, 'national_attitude_id')
                ->radioList($attitudeArray, [
                    'item' => static function ($index, $model, $name, $checked, $value) {
                        return '<div class="hidden-lg hidden-md hidden-sm col-xs-3"></div><div class="col-lg-2 col-md-2 col-sm-3 col-xs-9">'
                            . Html::radio($name, $checked, [
                                'index' => $index,
                                'label' => $model,
                                'value' => $value,
                            ])
                            . '</div>';
                    }
                ])
                ->label(false) ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?= Html::submitButton(Yii::t('frontend', 'views.national.view.submit'), ['class' => 'btn margin']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end() ?>
<?php endif ?>
<?php if ($notificationArray) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <ul>
                <?php foreach ($notificationArray as $item) : ?>
                    <li><?= $item ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
<?php endif ?>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//national/_national-links') ?>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'class' => SerialColumn::class,
                'contentOptions' => ['class' => 'text-center'],
                'footer' => '#',
                'header' => '#',
            ],
            [
                'attribute' => 'squad',
                'footer' => Yii::t('frontend', 'views.th.player'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.player'),
                'value' => static function (Player $model) {
                    return $model->getPlayerLink()
                        . $model->iconPension()
                        . $model->iconInjury()
                        . '<br><span class="font-grey text-size-3">'
                        . $model->team->getTeamLink()
                        . '</span>';
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
                'attribute' => 'power_nominal',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.power'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.nominal-power')],
                'format' => 'raw',
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.nominal-power')],
                'label' => Yii::t('frontend', 'views.th.power'),
                'value' => static function (Player $model) {
                    return $model->powerNominal();
                }
            ],
            [
                'attribute' => 'tire',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.tire'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.tire')],
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.tire')],
                'label' => Yii::t('frontend', 'views.th.tire'),
                'value' => static function (Player $model) {
                    return $model->playerTire();
                }
            ],
            [
                'attribute' => 'physical',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.physical'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.physical')],
                'format' => 'raw',
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.physical')],
                'label' => Yii::t('frontend', 'views.th.physical'),
                'value' => static function (Player $model) {
                    return $model->playerPhysical();
                }
            ],
            [
                'attribute' => 'power_real',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.real-power'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.real-power')],
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.real-power')],
                'label' => Yii::t('frontend', 'views.th.real-power'),
                'value' => static function (Player $model) use ($national) {
                    return $national->myTeamOrVice() ? $model->power_real : '~' . $model->power_nominal;
                }
            ],
            [
                'attribute' => 'special',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.th.special'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.special')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.special')],
                'label' => Yii::t('frontend', 'views.th.special'),
                'value' => static function (Player $model) {
                    return $model->special();
                }
            ],
            [
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
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.th.row'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.row')],
                'headerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.row')],
                'label' => Yii::t('frontend', 'views.th.row'),
                'value' => static function (Player $model) {
                    return $model->playerGameRow();
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'rowOptions' => static function (Player $model) use ($national) {
                $result = [];
                if ($model->nationalSquad && $national->myTeamOrVice()) {
                    $result['style'] = ['background-color' => '#' . $model->nationalSquad->color];
                }
                return $result;
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
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//national/_national-links') ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table') ?>
