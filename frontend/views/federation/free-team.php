<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Federation;
use common\models\db\Team;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var Federation $federation
 * @var View $this
 */

print $this->render('_federation', [
    'federation' => $federation,
]);

?>
<div class="row margin-top">
    <?php

    try {
        $columns = [
            [
                'attribute' => 'team',
                'footer' => 'Команда',
                'format' => 'raw',
                'label' => 'Команда',
                'value' => static function (Team $model) {
                    return $model->getTeamLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Рекомендация',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-40'],
                'label' => 'Рекомендация',
                'value' => static function (Team $model) {
                    return $model->recommendation ? $model->recommendation->user->getUserLink() : '-';
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => '',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1'],
                'label' => '',
                'value' => static function (Team $model) {
                    if ($model->recommendation) {
                        $result = Html::a(
                            '<i class="fa fa-minus-circle"></i>',
                            ['recommendation-delete', 'id' => $model->stadium->city->country->federation->id, 'teamId' => $model->id],
                            ['title' => 'Удалить рекомендацию']
                        );
                    } else {
                        $result = Html::a(
                            '<i class="fa fa-plus-circle"></i>',
                            ['recommendation-create', 'id' => $model->stadium->city->country->federation->id, 'teamId' => $model->id],
                            ['title' => 'Добавить рекомендацию']
                        );
                    }

                    return $result;
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
<?= $this->render('//site/_show-full-table') ?>

