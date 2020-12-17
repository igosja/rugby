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
        <?= Html::a('Список', ['index'], ['class' => 'btn btn-default']) ?>
    </li>
    <li>
        <?= Html::a('Одобрить', ['accept', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </li>
    <li>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </li>
</ul>
<div class="row">
    <?php

    try {
        $attributes = [
            [
                'captionOptions' => ['class' => 'col-lg-6'],
                'label' => 'ID',
                'value' => static function (Logo $model) {
                    return $model->id;
                },
            ],
            [
                'label' => 'Время заявки',
                'value' => static function (Logo $model) {
                    return FormatHelper::asDateTime($model->date);
                },
            ],
            [
                'format' => 'raw',
                'label' => 'Пользователь',
                'value' => static function (Logo $model) {
                    return $model->team->getTeamImageLink();
                },
            ],
            [
                'format' => 'raw',
                'label' => 'Старое фото',
                'value' => static function (Logo $model) {
                    if (file_exists(Yii::getAlias('@frontend') . '/web/img/team/125/' . $model->team_id . '.png')) {
                        return Html::img(
                            '/img/team/125/' . $model->team_id . '.png?v=' . filemtime(Yii::getAlias('@frontend') . '/web/img/team/125/' . $model->team_id . '.png'),
                            [
                                'alt' => Html::encode($model->team->name),
                                'title' => Html::encode($model->team->name),
                            ]
                        );
                    }
                    return '';
                },
            ],
            [
                'format' => 'raw',
                'label' => 'Новое фото',
                'value' => static function (Logo $model) {
                    if (file_exists(Yii::getAlias('@frontend') . '/web/upload/img/team/125/' . $model->team_id . '.png')) {
                        return Html::img(
                            '/upload/img/team/125/' . $model->team_id . '.png?v=' . filemtime(Yii::getAlias('@frontend') . '/web/upload/img/team/125/' . $model->team_id . '.png'),
                            [
                                'alt' => Html::encode($model->team->name),
                                'title' => Html::encode($model->team->name),
                            ]
                        );
                    }
                    return '';
                },
            ],
            [
                'label' => 'Комментарий',
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
