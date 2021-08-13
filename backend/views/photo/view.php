<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Logo;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var Logo $model
 * @var View $this
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h3 class="page-header"><?= Html::encode($this->title) ?></h3>
    </div>
</div>
<ul class="list-inline preview-links text-center">
    <li>
        <?= Html::a('List', ['index'], ['class' => 'btn btn-default']) ?>
    </li>
    <li>
        <?= Html::a('Accept', ['accept', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </li>
    <li>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </li>
</ul>
<div class="row">
    <?php

    try {
        $attributes = [
            [
                'captionOptions' => ['class' => 'col-lg-6'],
                'label' => 'Id',
                'value' => static function (Logo $model) {
                    return $model->id;
                },
            ],
            [
                'label' => 'Application time',
                'value' => static function (Logo $model) {
                    return FormatHelper::asDateTime($model->date);
                },
            ],
            [
                'format' => 'raw',
                'label' => 'User',
                'value' => static function (Logo $model) {
                    return $model->user->getUserLink();
                },
            ],
            [
                'format' => 'raw',
                'label' => 'Old photo',
                'value' => static function (Logo $model) {
                    if (file_exists(Yii::getAlias('@frontend') . '/web/img/user/125/' . $model->user_id . '.png')) {
                        return Html::img(
                            '/img/user/125/' . $model->user_id . '.png?v=' . filemtime(Yii::getAlias('@frontend') . '/web/img/user/125/' . $model->user_id . '.png'),
                            [
                                'alt' => Html::encode($model->user->login),
                                'title' => Html::encode($model->user->login),
                            ]
                        );
                    }
                    return '';
                },
            ],
            [
                'format' => 'raw',
                'label' => 'New photo',
                'value' => static function (Logo $model) {
                    if (file_exists(Yii::getAlias('@frontend') . '/web/upload/img/user/125/' . $model->user_id . '.png')) {
                        return Html::img(
                            '/upload/img/user/125/' . $model->user_id . '.png?v=' . filemtime(Yii::getAlias('@frontend') . '/web/upload/img/user/125/' . $model->user_id . '.png'),
                            [
                                'alt' => Html::encode($model->user->login),
                                'title' => Html::encode($model->user->login),
                            ]
                        );
                    }
                    return '';
                },
            ],
            [
                'label' => 'Comment',
                'value' => static function (Logo $model) {
                    return nl2br($model->text);
                },
            ],
        ];
        print DetailView::widget([
            'attributes' => $attributes,
            'model' => $model,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
