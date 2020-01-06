<?php

use common\components\helpers\ErrorHelper;
use common\models\db\News;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var News $model
 * @var View $this
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h3 class="page-header"><?= Html::encode($this->title); ?></h3>
    </div>
</div>
<ul class="list-inline preview-links text-center">
    <li>
        <?= Html::a('Список', ['news/index'], ['class' => 'btn btn-default']); ?>
    </li>
    <li>
        <?= Html::a('Изменить', ['news/update', 'id' => $model->news_id], ['class' => 'btn btn-default']); ?>
    </li>
    <li>
        <?= Html::a('Удалить', ['news/delete', 'id' => $model->news_id], ['class' => 'btn btn-default']); ?>
    </li>
</ul>
<div class="row">
    <?php

    try {
        $attributes = [
            'news_id',
            'news_date:datetime',
            'news_title',
            'news_text',
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
