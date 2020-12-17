<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Loan;
use frontend\models\search\LoanSearch;
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
            <h1>Список хоккеистов, выставленных на аренду</h1>
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
<?php ActiveForm::end() ?>
<?php if ($myPlayerArray) : ?>
    <div class="row margin-top-small">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-hover">
                <tr>
                    <th class="col-3">№</th>
                    <th class="col-20">Ваши игроки</th>
                    <th class="col-1 hidden-xs" title="Национальность">Нац</th>
                    <th class="col-5" title="Позиция">Поз</th>
                    <th class="col-5" title="Возраст">В</th>
                    <th class="col-5" title="Сила">С</th>
                    <th class="col-10 hidden-xs" title="Спецвозможности">Спец</th>
                    <th class="hidden-xs">Команда</th>
                    <th class="col-5" title="Срок аренды (календарных дней)">Дней</th>
                    <th class="col-10" title="Минимальная запрашиваемая цена">Цена</th>
                    <th class="col-10" title="Дата проведения торгов">Торги</th>
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
                    <th class="col-3">№</th>
                    <th class="col-20">Ваши заявки</th>
                    <th class="col-1 hidden-xs" title="Национальность">Нац</th>
                    <th class="col-5" title="Позиция">Поз</th>
                    <th class="col-5" title="Возраст">В</th>
                    <th class="col-5" title="Сила">С</th>
                    <th class="col-10 hidden-xs" title="Спецвозможности">Спец</th>
                    <th class="hidden-xs">Команда</th>
                    <th class="col-5" title="Срок аренды (календарных дней)">Дней</th>
                    <th class="col-10" title="Минимальная запрашиваемая цена">Цена</th>
                    <th class="col-10" title="Дата проведения торгов">Торги</th>
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
                    'footer' => '№',
                    'header' => '№',
                    'headerOptions' => ['class' => 'col-3'],
                ],
                [
                    'footer' => 'Игрок',
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-20'],
                    'label' => 'Игрок',
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
                    'footer' => 'Нац',
                    'footerOptions' => ['class' => 'col-1 hidden-xs', 'title' => 'Национальность'],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => 'Национальность'],
                    'label' => 'Нац',
                    'value' => static function (Loan $model) {
                        return $model->player->country->getImageLink();
                    }
                ],
                [
                    'attribute' => 'position',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Поз',
                    'footerOptions' => ['title' => 'Позиция'],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-5', 'title' => 'Позиция'],
                    'label' => 'Поз',
                    'value' => static function (Loan $model) {
                        return $model->player->position();
                    }
                ],
                [
                    'attribute' => 'age',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'В',
                    'footerOptions' => ['title' => 'Возраст'],
                    'headerOptions' => ['class' => 'col-5', 'title' => 'Возраст'],
                    'label' => 'В',
                    'value' => static function (Loan $model) {
                        return $model->player->age;
                    }
                ],
                [
                    'attribute' => 'power',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'С',
                    'footerOptions' => ['title' => 'Сила'],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'col-5', 'title' => 'Сила'],
                    'label' => 'С',
                    'value' => static function (Loan $model) {
                        return $model->player->power_nominal;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-center hidden-xs'],
                    'footer' => 'Спец',
                    'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Спецвозможности'],
                    'headerOptions' => ['class' => 'col-10 hidden-xs', 'title' => 'Спецвозможности'],
                    'label' => 'Спец',
                    'value' => static function (Loan $model) {
                        return $model->player->special();
                    }
                ],
                [
                    'contentOptions' => ['class' => 'hidden-xs'],
                    'footer' => 'Команда',
                    'footerOptions' => ['class' => 'hidden-xs'],
                    'format' => 'raw',
                    'headerOptions' => ['class' => 'hidden-xs'],
                    'label' => 'Команда',
                    'value' => static function (Loan $model) {
                        return $model->teamSeller->getTeamImageLink();
                    }
                ],
                [
                    'attribute' => 'days',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Дней',
                    'footerOptions' => ['title' => 'Срок аренды (календарных дней)'],
                    'headerOptions' => ['class' => 'col-5', 'title' => 'Срок аренды (календарных дней)'],
                    'label' => 'Дней',
                    'value' => static function (Loan $model) {
                        return $model->day_min . '-' . $model->day_max;
                    }
                ],
                [
                    'attribute' => 'price',
                    'contentOptions' => ['class' => 'text-right'],
                    'footer' => 'Цена',
                    'footerOptions' => ['title' => 'Минимальная запрашиваемая цена за 1 день аренды'],
                    'headerOptions' => ['class' => 'col-10', 'title' => 'Минимальная запрашиваемая цена за 1 день аренды'],
                    'label' => 'Цена',
                    'value' => static function (Loan $model) {
                        return FormatHelper::asCurrency($model->price_seller);
                    }
                ],
                [
                    'attribute' => 'loan_id',
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Торги',
                    'footerOptions' => ['title' => 'Дата проведения торгов'],
                    'headerOptions' => ['class' => 'col-10', 'title' => 'Дата проведения торгов'],
                    'label' => 'Торги',
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