<?php

// TODO refactor

/**
 * @var string $chapter
 * @var ArrayDataProvider $dataProvider
 */

use common\components\helpers\ErrorHelper;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\widgets\ListView;

?>

<div class="site-index">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
        </div>
    </div>
    <ul class="list-inline preview-links text-center">
        <li>
            <?= Html::a('Clear', ['clear', 'chapter' => $chapter], ['class' => 'btn btn-default']) ?>
        </li>
    </ul>
    <div class="row">
        <div class="col-lg-12">
            <?php try {
                ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_log',
                ]);
            } catch (Exception $e) {
                ErrorHelper::log($e);
            } ?>
        </div>
    </div>
</div>
