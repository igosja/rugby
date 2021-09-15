<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Federation;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\ListView;

/**
 * @var ActiveDataProvider $dataProvider
 * @var Federation $federation
 * @var View $this
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
            'itemOptions' => static function ($model, $key, $index) {
                $class = ['row', 'border-top'];
                if ($index % 2) {
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