<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Federation;
use common\models\db\Recommendation;
use common\models\db\Team;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var Federation $federation
 * @var Recommendation $model
 * @var Team $team
 * @var array $userArray
 */

print $this->render('_federation', [
    'federation' => $federation,
]);

?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-bordered table-hover">
            <tr>
                <th><?= Yii::t('frontend', 'views.federation.recommendation-create.title', ['team' => $team->name]) ?></th>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'errorOptions' => [
                    'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 xs-text-center notification-error',
                    'tag' => 'div'
                ],
                'labelOptions' => ['class' => 'strong'],
                'options' => ['class' => 'row'],
                'template' =>
                    '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">{label}</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">{input}</div>
                    {error}',
            ],
        ]) ?>
        <?php

        try {
            print $form
                ->field($model, 'user_id')
                ->widget(Select2::class, ['data' => $userArray]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton(Yii::t('frontend', 'views.federation.recommendation-create.submit'), ['class' => 'btn margin']) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
