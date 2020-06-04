<?php

use common\components\helpers\ErrorHelper;
use common\models\db\Team;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var ActiveDataProvider $dataProvider
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1>Команды</h1>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'footer' => 'Страна',
                'format' => 'raw',
                'label' => 'Страна',
                'value' => function (Team $model) {
                    return $model->stadium->city->country->countryImage()
                        . ' ' . Html::a(
                            $model->stadium->city->country->country_name,
                            ['federation/team', 'id' => $model->stadium->city->country->country_id]
                        );
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Команды',
                'headerOptions' => ['class' => 'col-25'],
                'label' => 'Команды',
                'value' => function (Team $model) {
                    return $model->team_player;
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'showFooter' => true,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
