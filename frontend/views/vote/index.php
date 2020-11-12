<?php

/**
 * @var ActiveDataProvider $dataProvider
 */

use common\components\helpers\ErrorHelper;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1>Опросы</h1>
    </div>
</div>
<div class="row">
    <?php

    try {
        print ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'row border-top'],
            'itemView' => '_vote',
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
