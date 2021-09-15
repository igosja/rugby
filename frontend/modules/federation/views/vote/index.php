<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Federation;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

/**
 * @var ActiveDataProvider $dataProvider
 * @var Federation $federation
 */

print $this->render('/default/_federation', [
    'federation' => $federation,
]);

?>
<div class="row">
    <?php

    try {
        print ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_vote',
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
