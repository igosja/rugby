<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Team;
use common\models\db\UserHoliday;
use frontend\models\forms\Holiday;
use kartik\select2\Select2;
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
                <th><?= Yii::t('frontend', 'views.user.holiday.th') ?></th>
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
        <?= Yii::t('frontend', 'views.user.holiday.p') ?>:
    </div>
</div>
<?= $form->field($model, 'isHoliday')->checkbox(['label' => false])->label(
    Yii::t('frontend', 'views.user.holiday.label')
) ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center margin-top strong">
        <?= Yii::t('frontend', 'views.user.holiday.vice') ?>:
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
            <?php

            try {
                print Select2::widget([
                    'data' => $item['userArray'],
                    'id' => 'vice-' . $team->id,
                    'name' => 'vice[' . $team->id . ']',
                    'options' => ['prompt' => Yii::t('frontend', 'views.user.holiday.prompt.no')],
                    'value' => $team->vice_user_id,
                ]);
            } catch (Exception $e) {
                ErrorHelper::log($e);
            }

            ?>
        </div>
    </div>
<?php endforeach ?>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton(Yii::t('frontend', 'views.user.holiday.submit'), ['class' => 'btn']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center margin-top strong">
        <?= Yii::t('frontend', 'views.user.holiday.history') ?>:
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.user.holiday.th.start'),
                'header' => Yii::t('frontend', 'views.user.holiday.th.start'),
                'value' => static function (UserHoliday $model) {
                    return FormatHelper::asDate($model->date_start);
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.user.holiday.th.end'),
                'header' => Yii::t('frontend', 'views.user.holiday.th.end'),
                'value' => static function (UserHoliday $model) {
                    if (!$model->date_end) {
                        return '-';
                    }
                    return FormatHelper::asDate($model->date_end);
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.user.holiday.th.duration'),
                'header' => Yii::t('frontend', 'views.user.holiday.th.duration'),
                'value' => static function (UserHoliday $model) {
                    if (!$model->date_end) {
                        return '-';
                    }
                    $date1 = new DateTime($model->date_start);
                    $date2 = new DateTime($model->date_end);
                    $interval = $date1->diff($date2);
                    return $interval->days . ' ' . Yii::t('frontend', 'views.user.holiday.days');
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
