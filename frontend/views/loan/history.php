<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Loan;
use frontend\models\search\LoanHistorySearch;
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
 * @var LoanHistorySearch $model
 * @var array $positionArray
 * @var View $this
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1><?= Yii::t('frontend', 'views.loan.history.h1') ?></h1>
    </div>
</div>
<div class="row margin-top-small text-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//loan/_links') ?>
    </div>
</div>
<?php $form = ActiveForm::begin([
    'action' => ['history'],
    'fieldConfig' => [
        'template' => '{input}',
    ],
    'method' => 'get',
]) ?>
<div class="row">
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
                'value' => static function (Loan $model) {
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
                'value' => static function (Loan $model) {
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
                'value' => static function (Loan $model) {
                    return $model->position();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.age'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'label' => Yii::t('frontend', 'views.th.age'),
                'value' => static function (Loan $model) {
                    return $model->age;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.power'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.power')],
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.power')],
                'label' => Yii::t('frontend', 'views.th.power'),
                'value' => static function (Loan $model) {
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
                'value' => static function (Loan $model) {
                    return $model->special();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => Yii::t('frontend', 'views.loan.history.th.seller'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'hidden-xs'],
                'label' => Yii::t('frontend', 'views.loan.history.th.seller'),
                'value' => function (Loan $model) {
                    return $model->teamSeller->getTeamImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => Yii::t('frontend', 'views.loan.history.th.buyer'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'hidden-xs'],
                'label' => Yii::t('frontend', 'views.loan.history.th.buyer'),
                'value' => static function (Loan $model) {
                    return $model->teamBuyer->getTeamImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => Yii::t('frontend', 'views.th.price'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.loan.history.title.price')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.loan.history.title.price')],
                'label' => Yii::t('frontend', 'views.th.price'),
                'value' => static function (Loan $model) {
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
                'footerOptions' => ['title' => Yii::t('frontend', 'views.loan.history.title.rating')],
                'format' => 'raw',
                'headerOptions' => ['title' => Yii::t('frontend', 'views.loan.history.title.rating')],
                'label' => '+/-',
                'value' => static function (Loan $model) {
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
