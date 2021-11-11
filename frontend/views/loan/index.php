<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Loan;
use frontend\models\search\LoanSearch;
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
 * @var LoanSearch $model
 * @var Loan[] $myApplicationArray
 * @var Loan[] $myPlayerArray
 * @var array $positionArray
 * @var View $this
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1><?= Yii::t('frontend', 'views.loan.index.h1') ?></h1>
    </div>
</div>
<div class="row margin-top-small text-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//loan/_links') ?>
    </div>
</div>
<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'fieldConfig' => [
        'template' => '{input}',
    ],
    'method' => 'get',
]) ?>
<div class="row flex">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
        <?= Yii::t('frontend', 'views.loan.search') ?>:
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
            'placeholder' => Yii::t('frontend', 'views.loan.placeholder.name'),
        ]) ?>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-7">
        <?= $form->field($model, 'surname')->textInput([
            'class' => 'form-control',
            'placeholder' => Yii::t('frontend', 'views.loan.placeholder.surname'),
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
            'placeholder' => Yii::t('frontend', 'views.loan.placeholder.age.min'),
            'type' => 'number',
        ]) ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <?= $form->field($model, 'ageMax')->textInput([
            'class' => 'form-control',
            'placeholder' => Yii::t('frontend', 'views.loan.placeholder.age.max'),
            'type' => 'number',
        ]) ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <?= $form->field($model, 'powerMin')->textInput([
            'class' => 'form-control',
            'placeholder' => Yii::t('frontend', 'views.loan.placeholder.power.min'),
            'type' => 'number',
            ]) ?>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
            <?= $form->field($model, 'powerMax')->textInput([
                'class' => 'form-control',
                'placeholder' => Yii::t('frontend', 'views.loan.placeholder.power.max'),
                'type' => 'number',
            ]) ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
            <?= $form->field($model, 'priceMin')->textInput([
                'class' => 'form-control',
                'placeholder' => Yii::t('frontend', 'views.loan.placeholder.price.min'),
                'type' => 'number',
            ]) ?>
        </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
        <?= $form->field($model, 'priceMax')->textInput([
            'class' => 'form-control',
            'placeholder' => Yii::t('frontend', 'views.loan.placeholder.price.max'),
            'type' => 'number',
        ]) ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
        <?= Html::submitButton(Yii::t('frontend', 'views.loan.submit'), ['class' => 'form-control submit-blue']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
<?php if ($myPlayerArray) : ?>
    <div class="row margin-top-small">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-hover">
                <tr>
                    <th class="col-3">#</th>
                    <th class="col-20"><?= Yii::t('frontend', 'views.loan.index.th.player') ?></th>
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
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.loan.index.title.day') ?>"><?= Yii::t('frontend', 'views.loan.index.th.day') ?></th>
                    <th class="col-10"
                        title="<?= Yii::t('frontend', 'views.loan.index.title.price') ?>"><?= Yii::t('frontend', 'views.loan.index.th.price') ?></th>
                    <th class="col-10"
                        title="<?= Yii::t('frontend', 'views.loan.index.title.deal') ?>"><?= Yii::t('frontend', 'views.loan.index.th.deal') ?></th>
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
                        <td class="hidden-xs"><?= $myPlayerArray[$i]->teamSeller->getTeamImageLink() ?></td>
                        <td class="text-center"><?= $myPlayerArray[$i]->day_min ?>
                            -<?= $myPlayerArray[$i]->day_max ?></td>
                        <td class="text-right"><?= FormatHelper::asCurrency($myPlayerArray[$i]->price_seller) ?></td>
                        <td class="text-center"><?php $myPlayerArray[$i]->dealDate() ?>
                        </td>
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
                    <th class="col-20"><?= Yii::t('frontend', 'views.loan.index.th.application') ?></th>
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
                    <th class="col-5"
                        title="<?= Yii::t('frontend', 'views.loan.index.title.day') ?>"><?= Yii::t('frontend', 'views.loan.index.th.day') ?></th>
                    <th class="col-10"
                        title="<?= Yii::t('frontend', 'views.loan.index.title.price') ?>"><?= Yii::t('frontend', 'views.th.price') ?></th>
                    <th class="col-10"
                        title="<?= Yii::t('frontend', 'views.loan.index.title.deal') ?>"><?= Yii::t('frontend', 'views.loan.index.th.deal') ?></th>
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
                        <td class="hidden-xs"><?= $myApplicationArray[$i]->teamSeller->getTeamImageLink() ?></td>
                        <td class="text-center"><?= $myApplicationArray[$i]->day_min ?>
                            -<?= $myApplicationArray[$i]->day_max ?></td>
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
                    'footer' => Yii::t('frontend', 'views.th.player'),
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-20'],
                    'label' => Yii::t('frontend', 'views.th.player'),
                    'value' => static function (Loan $model) {
                        return Html::a(
                            $model->player->playerName(),
                            ['player/loan', 'id' => $model->player->id]
                        );
                    }
                ],
                [
                    'attribute' => 'country',
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => Yii::t('frontend', 'views.th.national'),
                    'footerOptions' => ['class' => 'col-1 hidden-xs', 'title' => Yii::t('frontend', 'views.title.national')],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => Yii::t('frontend', 'views.title.national')],
                    'label' => Yii::t('frontend', 'views.th.national'),
                    'value' => static function (Loan $model) {
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
                    'value' => static function (Loan $model) {
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
                    'value' => static function (Loan $model) {
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
                    'value' => static function (Loan $model) {
                        return $model->player->power_nominal;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-center hidden-xs'],
                    'footer' => Yii::t('frontend', 'views.th.special'),
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.special')],
                    'headerOptions' => ['class' => 'col-10 hidden-xs', 'title' => Yii::t('frontend', 'views.title.special')],
                    'label' => Yii::t('frontend', 'views.th.special'),
                    'value' => static function (Loan $model) {
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
                    'value' => static function (Loan $model) {
                        return $model->teamSeller->getTeamImageLink();
                    }
                ],
                [
                    'attribute' => 'days',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.loan.index.th.day'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.loan.index.title.day')],
                    'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.loan.index.title.day')],
                    'label' => Yii::t('frontend', 'views.loan.index.th.day'),
                    'value' => static function (Loan $model) {
                        return $model->day_min . '-' . $model->day_max;
                    }
                ],
                [
                    'attribute' => 'price',
                    'contentOptions' => ['class' => 'text-right'],
                    'footer' => Yii::t('frontend', 'views.loan.index.th.price'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.loan.index.title.price')],
                    'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.loan.index.title.price')],
                    'label' => Yii::t('frontend', 'views.loan.index.th.price'),
                    'value' => static function (Loan $model) {
                        return FormatHelper::asCurrency($model->price_seller);
                    }
                ],
                [
                    'attribute' => 'loan_id',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.loan.index.th.deal'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.loan.index.title.deal')],
                    'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.loan.index.title.deal')],
                    'label' => Yii::t('frontend', 'views.loan.index.th.deal'),
                    'value' => static function (Loan $model) {
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
