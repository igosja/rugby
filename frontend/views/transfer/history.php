<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Transfer;
use frontend\models\search\TransferHistorySearch;
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
 * @var TransferHistorySearch $model
 * @var array $positionArray
 * @var View $this
 */

?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1><?= Yii::t('frontend', 'views.transfer.history.h1') ?></h1>
        </div>
    </div>
    <div class="row margin-top-small text-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= $this->render('//transfer/_links') ?>
        </div>
    </div>
<?php $form = ActiveForm::begin([
    'action' => ['transfer/history'],
    'fieldConfig' => [
        'template' => '{input}',
    ],
    'method' => 'get',
]) ?>
    <div class="row flex">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
            <?= Yii::t('frontend', 'views.transfer.history.search') ?>:
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
                'placeholder' => Yii::t('frontend', 'views.transfer.history.placeholder.name'),
            ]) ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-7">
            <?= $form->field($model, 'surname')->textInput([
                'class' => 'form-control',
                'placeholder' => Yii::t('frontend', 'views.transfer.history.placeholder.surname'),
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
                'placeholder' => Yii::t('frontend', 'views.transfer.history.placeholder.age.min'),
                'type' => 'number',
            ]) ?>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
            <?= $form->field($model, 'ageMax')->textInput([
                'class' => 'form-control',
                'placeholder' => Yii::t('frontend', 'views.transfer.history.placeholder.age.max'),
                'type' => 'number',
            ]) ?>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
            <?= $form->field($model, 'powerMin')->textInput([
                'class' => 'form-control',
                'placeholder' => Yii::t('frontend', 'views.transfer.history.placeholder.power.min'),
                'type' => 'number',
            ]) ?>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
            <?= $form->field($model, 'powerMax')->textInput([
                'class' => 'form-control',
                'placeholder' => Yii::t('frontend', 'views.transfer.history.placeholder.power.max'),
                'type' => 'number',
            ]) ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
            <?= $form->field($model, 'priceMin')->textInput([
                'class' => 'form-control',
                'placeholder' => Yii::t('frontend', 'views.transfer.history.placeholder.price.min'),
                'type' => 'number',
            ]) ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
            <?= $form->field($model, 'priceMax')->textInput([
                'class' => 'form-control',
                'placeholder' => Yii::t('frontend', 'views.transfer.history.placeholder.price.max'),
                'type' => 'number',
            ]) ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
            <?= Html::submitButton(Yii::t('frontend', 'views.transfer.history.submit'), ['class' => 'form-control submit-blue']) ?>
        </div>
    </div>
<?php ActiveForm::end() ?>
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
                    'footer' => Yii::t('frontend', 'views.th.player'),
                    'format' => 'raw',
                    'label' => Yii::t('frontend', 'views.th.player'),
                    'value' => static function (Transfer $model) {
                        return Html::a(
                            $model->player->playerName(),
                            ['view', 'id' => $model->id]
                        );
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => Yii::t('frontend', 'views.th.national'),
                    'footerOptions' => ['class' => 'col-1 hidden-xs', 'title' => Yii::t('frontend', 'views.title.national')],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => Yii::t('frontend', 'views.title.national')],
                    'label' => Yii::t('frontend', 'views.th.national'),
                    'value' => static function (Transfer $model) {
                        return $model->player->country->getImageLink();
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.th.position'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                    'format' => 'raw',
                    'headerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                    'label' => Yii::t('frontend', 'views.th.position'),
                    'value' => static function (Transfer $model) {
                        return $model->position();
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.th.age'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                    'headerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                    'label' => Yii::t('frontend', 'views.th.age'),
                    'value' => static function (Transfer $model) {
                        return $model->age;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.th.power'),
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.title.power')],
                    'headerOptions' => ['title' => Yii::t('frontend', 'views.title.power')],
                    'label' => Yii::t('frontend', 'views.th.power'),
                    'value' => static function (Transfer $model) {
                        return $model->power;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-center hidden-xs'],
                    'footer' => Yii::t('frontend', 'views.th.special'),
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.special')],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.special')],
                    'label' => Yii::t('frontend', 'views.th.special'),
                    'value' => static function (Transfer $model) {
                        return $model->special();
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs'],
                    'footer' => Yii::t('frontend', 'views.transfer.history.th.seller'),
                    'footerOptions' => ['class' => 'hidden-xs'],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'hidden-xs'],
                    'label' => Yii::t('frontend', 'views.transfer.history.th.seller'),
                    'value' => static function (Transfer $model) {
                        return $model->teamSeller->getTeamLink();
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs'],
                    'footer' => Yii::t('frontend', 'views.transfer.history.th.buyer'),
                    'footerOptions' => ['class' => 'hidden-xs'],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'hidden-xs'],
                    'label' => Yii::t('frontend', 'views.transfer.history.th.buyer'),
                    'value' => static function (Transfer $model) {
                        return $model->teamBuyer->getTeamLink();
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-right'],
                    'footer' => Yii::t('frontend', 'views.th.price'),
                    'format' => 'raw',
                    'label' => Yii::t('frontend', 'views.th.price'),
                    'value' => static function (Transfer $model) {
                        $class = '';
                        if ($model->cancel) {
                            $class = 'del';
                        }
                        return '<span class="' . $class . '">' . FormatHelper::asCurrency($model->price_buyer) . '</span>';
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => '+/-',
                    'footerOptions' => ['title' => Yii::t('frontend', 'views.transfer.history.title.rating')],
                    'format' => 'raw',
                    'headerOptions' => ['title' => Yii::t('frontend', 'views.transfer.history.title.rating')],
                    'label' => '+/-',
                    'value' => static function (Transfer $model) {
                        return $model->rating();
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