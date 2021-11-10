<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Support;
use common\models\db\User;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var ActiveDataProvider $dataProvider
 * @var Support $model
 * @var View $this
 * @var User $user
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h3 class="page-header"><?= Html::encode($user->login) ?></h3>
    </div>
</div>
<ul class="list-inline preview-links text-center">
    <li>
        <?= Html::a('List', ['support/index'], ['class' => 'btn btn-default']) ?>
    </li>
</ul>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'format' => 'raw',
                'value' => static function (Support $model) {
                    $result = FormatHelper::asDateTime($model->date);
                    if ($model->is_question) {
                        if ($model->user_id) {
                            $result .= ' ' . $model->user->getUserLink();
                        } else {
                            $result .= ' ' . $model->presidentUser->getUserLink() . ' (president)';
                        }
                    } else {
                        $result .= ' ' . $model->adminUser->getUserLink();
                    }
                    $result .= '<br/>' . nl2br($model->text);
                    return $result;
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'showHeader' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php $form = ActiveForm::begin() ?>
        <?= $form->field($model, 'text')->textarea()->label(false) ?>
        <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton('Send', ['class' => 'btn btn-default']) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>