<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Transfer;
use frontend\models\search\TransferSearch;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var array $countryArray
 * @var ActiveDataProvider $dataProvider
 * @var TransferSearch $model
 * @var Transfer[] $myApplicationArray
 * @var Transfer[] $myPlayerArray
 * @var array $positionArray
 * @var View $this
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1><?= Yii::t('frontend', 'views.transfer.index.h1') ?></h1>
    </div>
</div>
<div class="row margin-top-small text-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//transfer/_links') ?>
    </div>
</div>
<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'fieldConfig' => [
        'template' => '{input}',
    ],
    'method' => 'get',
]) ?>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
        <?= Yii::t('frontend', 'views.transfer.index.search') ?>:
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
        <?php

        try {
            print $form
                ->field($model, 'country')
                ->widget(Select2::class, [
                    'data' => $countryArray,
                    'options' => ['prompt' => Yii::t('frontend', 'views.loan.prompt.national')]
                ]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-5">
        <?= $form->field($model, 'name')->textInput([
            'class' => 'form-control',
            'placeholder' => Yii::t('frontend', 'views.transfer.index.placeholder.name'),
        ]) ?>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-7">
        <?= $form->field($model, 'surname')->textInput([
            'class' => 'form-control',
            'placeholder' => Yii::t('frontend', 'views.transfer.index.placeholder.surname'),
        ]) ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
        <?php

        try {
            print $form
                ->field($model, 'position')
                ->widget(Select2::class, [
                    'data' => $positionArray,
                    'options' => ['prompt' => Yii::t('frontend', 'views.loan.prompt.position')]
                ]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <?= $form->field($model, 'ageMin')->textInput([
            'class' => 'form-control',
            'placeholder' => Yii::t('frontend', 'views.transfer.index.placeholder.age.min'),
            'type' => 'number',
        ]) ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <?= $form->field($model, 'ageMax')->textInput([
            'class' => 'form-control',
            'placeholder' => Yii::t('frontend', 'views.transfer.index.placeholder.age.max'),
            'type' => 'number',
        ]) ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <?= $form->field($model, 'powerMin')->textInput([
            'class' => 'form-control',
            'placeholder' => Yii::t('frontend', 'views.transfer.index.placeholder.power.min'),
            'type' => 'number',
        ]) ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <?= $form->field($model, 'powerMax')->textInput([
            'class' => 'form-control',
            'placeholder' => Yii::t('frontend', 'views.transfer.index.placeholder.power.max'),
            'type' => 'number',
        ]) ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
        <?= $form->field($model, 'priceMin')->textInput([
            'class' => 'form-control',
            'placeholder' => Yii::t('frontend', 'views.transfer.index.placeholder.price.min'),
            'type' => 'number',
        ]) ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
        <?= $form->field($model, 'priceMax')->textInput([
            'class' => 'form-control',
            'placeholder' => Yii::t('frontend', 'views.transfer.index.placeholder.price.max'),
            'type' => 'number',
        ]) ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
        <?= Html::submitButton(Yii::t('frontend', 'views.transfer.index.submit'), ['class' => 'form-control submit-blue']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
<?php if ($myPlayerArray) : ?>
    <div class="row margin-top-small">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-hover">
                <tr>
                    <th class="col-3">#</th>
                    <th class="col-25"><?= Yii::t('frontend', 'views.transfer.index.th.your.player') ?></th>
                    <th class="col-1 hidden-xs"
                        title="<?= Yii::t('frontend', 'views.title.national') ?>"><?= Yii::t('frontend', 'views.th.national') ?></th>
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.title.position') ?>"><?= Yii::t('frontend', 'views.th.position') ?></th>
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.title.age') ?>"><?= Yii::t('frontend', 'views.th.age') ?></th>
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.title.power') ?>"><?= Yii::t('frontend', 'views.th.power') ?></th>
                    <th class="col-10 hidden-xs"
                        title="<?= Yii::t('frontend', 'views.title.special') ?>"><?= Yii::t('frontend', 'views.th.special') ?></th>
                    <th class="hidden-xs"><?= Yii::t('frontend', 'views.th.team') ?></th>
                    <th class="col-10"
                        title="<?= Yii::t('frontend', 'views.transfer.index.title.price') ?>"><?= Yii::t('frontend', 'views.th.price') ?></th>
                    <th class="col-10"
                        title="<?= Yii::t('frontend', 'views.transfer.index.title.date') ?>"><?= Yii::t('frontend', 'views.transfer.index.th.date') ?></th>
                </tr>
                <?php for ($i = 0, $iMax = count($myPlayerArray); $i < $iMax; $i++) : ?>
                    <tr>
                        <td class="text-center"><?= ($i + 1) ?></td>
                        <td><?= $myPlayerArray[$i]->player->getPlayerLink() ?></td>
                        <td class="hidden-xs text-center"><?= $myPlayerArray[$i]->player->country->getImageLink() ?></td>
                        <td class="text-center"><?= $myPlayerArray[$i]->player->position() ?></td>
                        <td class="text-center"><?= $myPlayerArray[$i]->player->age ?></td>
                        <td class="text-center"><?= $myPlayerArray[$i]->player->power_nominal ?></td>
                        <td class="text-center hidden-xs"><?= $myPlayerArray[$i]->player->special() ?></td>
                        <td class="hidden-xs"><?= $myPlayerArray[$i]->teamSeller->getTeamLink() ?></td>
                        <td class="text-right"><?= FormatHelper::asCurrency($myPlayerArray[$i]->price_seller) ?></td>
                        <td class="text-center"><?= $myPlayerArray[$i]->dealDate() ?></td>
                    </tr>
                <?php endfor ?>
            </table>
        </div>
    </div>
<?php endif ?>
<?php if ($myApplicationArray) : ?>
    <div class="row margin-top-small">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-hover">
                <tr>
                    <th class="col-3">#</th>
                    <th class="col-25"><?= Yii::t('frontend', 'views.transfer.index.th.your.application') ?></th>
                    <th class="col-1 hidden-xs"
                        title="<?= Yii::t('frontend', 'views.title.national') ?>"><?= Yii::t('frontend', 'views.th.national') ?></th>
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.title.position') ?>"><?= Yii::t('frontend', 'views.th.position') ?></th>
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.title.age') ?>"><?= Yii::t('frontend', 'views.th.age') ?></th>
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.title.power') ?>"><?= Yii::t('frontend', 'views.th.power') ?></th>
                    <th class="col-10 hidden-xs"
                        title="<?= Yii::t('frontend', 'views.title.special') ?>"><?= Yii::t('frontend', 'views.th.special') ?></th>
                    <th class="hidden-xs"><?= Yii::t('frontend', 'views.th.team') ?></th>
                    <th class="col-10"
                        title="<?= Yii::t('frontend', 'views.transfer.index.title.price') ?>"><?= Yii::t('frontend', 'views.th.price') ?></th>
                    <th class="col-10"
                        title="<?= Yii::t('frontend', 'views.transfer.index.title.date') ?>"><?= Yii::t('frontend', 'views.transfer.index.th.date') ?></th>
                </tr>
                <?php for ($i = 0, $iMax = count($myApplicationArray); $i < $iMax; $i++) : ?>
                    <tr>
                        <td class="text-center"><?= ($i + 1) ?></td>
                        <td><?= $myApplicationArray[$i]->player->getPlayerLink() ?></td>
                        <td class="hidden-xs text-center"><?= $myApplicationArray[$i]->player->country->getImageLink() ?></td>
                        <td class="text-center"><?= $myApplicationArray[$i]->player->position() ?></td>
                        <td class="text-center"><?= $myApplicationArray[$i]->player->age ?></td>
                        <td class="text-center"><?= $myApplicationArray[$i]->player->power_nominal ?></td>
                        <td class="text-center hidden-xs"><?= $myApplicationArray[$i]->player->special() ?></td>
                        <td class="hidden-xs"><?= $myApplicationArray[$i]->teamSeller->getTeamLink() ?></td>
                        <td class="text-right"><?= FormatHelper::asCurrency($myApplicationArray[$i]->price_seller) ?></td>
                        <td class="text-center"><?= $myApplicationArray[$i]->dealDate() ?></td>
                    </tr>
                <?php endfor ?>
            </table>
        </div>
    </div>
<?php endif ?>
<div class="row margin-top-small">
    <?php

    try {
        $columns = [
            [
                'class' => SerialColumn::class,
                'contentOptions' => ['class' => 'text-center'],
                'footer' => '#',
                'header' => '#',
                'headerOptions' => ['class' => 'col-3'],
            ],
            [
                'attribute' => 'id',
                'footer' => Yii::t('frontend', 'views.th.player'),
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-25'],
                'label' => Yii::t('frontend', 'views.th.player'),
                'value' => static function (Transfer $model) {
                    return Html::a(
                        $model->player->playerName(),
                        ['player/transfer', 'id' => $model->player->id]
                    );
                }
            ],
            [
                'attribute' => 'country',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.th.national'),
                'footerOptions' => ['class' => 'col-1 hidden-xs', 'title' => Yii::t('frontend', 'views.title.national')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.national')],
                'label' => Yii::t('frontend', 'views.th.national'),
                'value' => static function (Transfer $model) {
                    return $model->player->country->getImageLink();
                }
            ],
            [
                'attribute' => 'position',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.position'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.position')],
                'label' => Yii::t('frontend', 'views.th.position'),
                'value' => static function (Transfer $model) {
                    return $model->player->position();
                }
            ],
            [
                'attribute' => 'age',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.age'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.age')],
                'label' => Yii::t('frontend', 'views.th.age'),
                'value' => static function (Transfer $model) {
                    return $model->player->age;
                }
            ],
            [
                'attribute' => 'power',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.power'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.power')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.power')],
                'label' => Yii::t('frontend', 'views.th.power'),
                'value' => static function (Transfer $model) {
                    return $model->player->power_nominal;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center hidden-xs'],
                'footer' => Yii::t('frontend', 'views.th.special'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.special')],
                'headerOptions' => ['class' => 'col-10 hidden-xs', 'title' => Yii::t('frontend', 'views.title.special')],
                'label' => Yii::t('frontend', 'views.th.special'),
                'value' => static function (Transfer $model) {
                    return $model->player->special();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => Yii::t('frontend', 'views.th.team'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'hidden-xs'],
                'label' => Yii::t('frontend', 'views.th.team'),
                'value' => static function (Transfer $model) {
                    return $model->teamSeller->getTeamLink();
                }
            ],
            [
                'attribute' => 'price',
                'contentOptions' => ['class' => 'text-right'],
                'footer' => Yii::t('frontend', 'views.th.price'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.transfer.index.title.price')],
                'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.transfer.index.title.price')],
                'label' => Yii::t('frontend', 'views.th.price'),
                'value' => static function (Transfer $model) {
                    return FormatHelper::asCurrency($model->price_seller);
                }
            ],
            [
                'attribute' => 'transfer_id',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.transfer.index.th.date'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.transfer.index.title.date')],
                'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.transfer.index.title.date')],
                'label' => Yii::t('frontend', 'views.transfer.index.th.date'),
                'value' => static function (Transfer $model) {
                    return $model->dealDate();
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'showFooter' => true,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<?= $this->render('//site/_show-full-table') ?>
