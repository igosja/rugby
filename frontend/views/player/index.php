<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Player;
use frontend\models\search\PlayerSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var array $countryArray
 * @var ActiveDataProvider $dataProvider
 * @var PlayerSearch $model
 * @var array $positionArray
 * @var View $this
 */

?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1>
                Список игроков
            </h1>
        </div>
    </div>
<?php $form = ActiveForm::begin([
    'action' => ['player/index'],
    'fieldConfig' => [
        'template' => '{input}',
    ],
    'method' => 'get',
]) ?>
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
            Условия поиска:
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <?= $form->field($model, 'country')->dropDownList(
                $countryArray,
                ['class' => 'form-control', 'prompt' => 'Национальность']
            ) ?>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-5">
            <?= $form->field($model, 'name')->textInput([
                'class' => 'form-control',
                'placeholder' => 'Имя',
            ]) ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-7">
            <?= $form->field($model, 'surname')->textInput([
                'class' => 'form-control',
                'placeholder' => 'Фамилия',
            ]) ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
            <?= $form->field($model, 'position')->dropDownList(
                $positionArray,
                ['class' => 'form-control', 'prompt' => 'Позиция']
            ) ?>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
            <?= $form->field($model, 'ageMin')->textInput([
                'class' => 'form-control',
                'placeholder' => 'Возраст, от',
                'type' => 'number',
            ]) ?>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
            <?= $form->field($model, 'ageMax')->textInput([
                'class' => 'form-control',
                'placeholder' => 'Возраст, до',
                'type' => 'number',
            ]) ?>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
            <?= $form->field($model, 'powerMin')->textInput([
                'class' => 'form-control',
                'placeholder' => 'Сила, от',
                'type' => 'number',
            ]) ?>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
            <?= $form->field($model, 'powerMax')->textInput([
                'class' => 'form-control',
                'placeholder' => 'Сила, до',
                'type' => 'number',
            ]) ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
            <?= $form->field($model, 'priceMin')->textInput([
                'class' => 'form-control',
                'placeholder' => 'Цена, от',
                'type' => 'number',
            ]) ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
            <?= $form->field($model, 'priceMax')->textInput([
                'class' => 'form-control',
                'placeholder' => 'Цена, до',
                'type' => 'number',
            ]) ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
            <?= Html::submitButton('Поиск', ['class' => 'form-control submit-blue']) ?>
        </div>
    </div>
<?php

// TODO refactor ActiveForm::end() ?>
    <div class="row">
        <?php

// TODO refactor

        try {
            $columns = [
                [
                    'class' => SerialColumn::class,
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => '№',
                    'header' => '№',
                ],
                [
                    'attribute' => 'surname',
                    'footer' => 'Игрок',
                    'format' => 'raw',
                    'label' => 'Игрок',
                    'value' => static function (Player $model) {
                        return $model->getPlayerLink();
                    }
                ],
                [
                    'attribute' => 'country',
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => 'Нац',
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Национальность'],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'hidden-xs col-1', 'title' => 'Национальность'],
                    'label' => 'Нац',
                    'value' => static function (Player $model) {
                        return $model->country->getImageLink();
                    }
                ],
                [
                    'attribute' => 'position',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Поз',
                    'footerOptions' => ['title' => 'Позиция'],
                    'format' => 'raw',
                    'headerOptions' => ['title' => 'Позиция'],
                    'label' => 'Поз',
                    'value' => static function (Player $model) {
                        return $model->position();
                    }
                ],
                [
                    'attribute' => 'age',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'В',
                    'footerOptions' => ['title' => 'Возраст'],
                    'headerOptions' => ['title' => 'Возраст'],
                    'label' => 'В',
                    'value' => static function (Player $model) {
                        return $model->age;
                    }
                ],
                [
                    'attribute' => 'power',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'С',
                    'footerOptions' => ['title' => 'Сила'],
                    'headerOptions' => ['title' => 'Сила'],
                    'label' => 'С',
                    'value' => static function (Player $model) {
                        return $model->power_nominal;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs text-center'],
                    'footer' => 'Спец',
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Спецсозможности'],
                    'header' => 'Спец',
                    'headerOptions' => ['class' => 'hidden-xs', 'title' => 'Спецсозможности'],
                    'value' => static function (Player $model) {
                        return $model->special();
                    }
                ],
                [
                    'attribute' => 'team',
                    'footer' => 'Команда',
                    'format' => 'raw',
                    'label' => 'Команда',
                    'value' => static function (Player $model) {
                        return $model->team->getTeamLink();
                    }
                ],
                [
                    'attribute' => 'price',
                    'contentOptions' => ['class' => 'hidden-xs text-right'],
                    'footer' => 'Цена',
                    'label' => 'Цена',
                    'value' => static function (Player $model) {
                        return FormatHelper::asCurrency($model->price);
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