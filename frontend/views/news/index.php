<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

/**
 * @var ActiveDataProvider $dataProvider
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1>Новости</h1>
    </div>
</div>
<div class="row">
    <?php

    try {
        print ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => static function ($model, $key, $index) {
                $class = ['row', 'border-top'];
                if ($model && $key && $index % 2) {
                    $class[] = 'div-odd';
                }
                return ['class' => $class];
            },
            'itemView' => '_news',
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
