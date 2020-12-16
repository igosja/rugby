<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Team;
use common\models\db\UserHoliday;
use frontend\models\forms\Holiday;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var ActiveDataProvider $dataProvider
 * @var Holiday $model
 * @var array $teamArray
 */

print $this->render('//user/_top');

?>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//user/_user-links') ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table">
            <tr>
                <th>Отпуск менеджера</th>
            </tr>
        </table>
    </div>
</div>
<?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'labelOptions' => ['class' => 'strong'],
        'options' => ['class' => 'row'],
        'template' =>
            '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center margin-top">
            {label}
            {input}
            </div>',
    ],
]) ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        На этой странице вы можете <span class="strong">изменить свои анкетные данные</span>:
    </div>
</div>
<?= $form->field($model, 'isHoliday')->checkbox(['label' => false])->label(
    'Поставьте здесь галочку, если собираетесь уехать в отпуск и временно не сможете управлять своими командами'
) ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center margin-top strong">
        Заместители:
    </div>
</div>
<?php foreach ($teamArray as $item) : ?>
    <?php
    /**
     * @var Team $team
     */
    $team = $item['team'];
    ?>
    <div class="row">
        <div class="col-lg-5 col-md-4 col-sm-4 col-xs-12 text-right">
            <?= Html::label(
                $team->name . ' (' . $team->stadium->city->country->name . ')',
                $team->id
            ) ?>
        </div>
        <div class="col-lg-3 col-md-5 col-sm-5 col-xs-12">
            <?= Html::dropDownList(
                'vice[' . $team->id . ']',
                $team->vice_user_id,
                $item['userArray'],
                ['prompt' => 'Нет', 'id' => 'vice-' . $team->id, 'class' => 'form-control']
            ) ?>
        </div>
    </div>
<?php endforeach ?>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center margin-top strong">
        История:
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Начало отпуска',
                'header' => 'Начало отпуска',
                'value' => static function (UserHoliday $model) {
                    return FormatHelper::asDate($model->date_start);
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Конец отпуска',
                'header' => 'Конец отпуска',
                'value' => static function (UserHoliday $model) {
                    if (!$model->date_end) {
                        return '-';
                    }
                    return FormatHelper::asDate($model->date_end);
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Длительность',
                'header' => 'Длительность',
                'value' => static function (UserHoliday $model) {
                    if (!$model->date_end) {
                        return '-';
                    }
                    $date1 = new DateTime('@' . $model->date_start);
                    $date2 = new DateTime('@' . $model->date_end);
                    $interval = $date1->diff($date2);
                    return $interval->days . ' d';
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
