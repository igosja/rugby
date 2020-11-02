<?php

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Team;
use common\models\db\TeamRequest;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var Team $model
 * @var ActiveDataProvider $myDataProvider
 * @var View $this
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h1>Получение команды</h1>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1'],
                'value' => function (TeamRequest $model) {
                    return Html::a(
                        '<i class="fa fa-times-circle"></i>',
                        ['team-request/delete', 'id' => $model->team_request_id],
                        ['title' => 'Удалить заявку']
                    );
                }
            ],
            [
                'footer' => 'Ваши заявки',
                'format' => 'raw',
                'label' => 'Ваши заявки',
                'value' => function (TeamRequest $model) {
                    return $model->team->teamLink('img');
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Vs',
                'label' => 'Vs',
                'value' => function (TeamRequest $model) {
                    return $model->team->team_power_vs;
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $myDataProvider,
            'showFooter' => true,
            'showOnEmpty' => false,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-1'],
                'value' => function (Team $model) {
                    return Html::a(
                        '<i class="fa fa-check-circle"></i>',
                        ['team-request/request', 'id' => $model->team_id],
                        ['title' => 'Выбрать']
                    );
                }
            ],
            [
                'attribute' => 'team',
                'footer' => 'Команда',
                'format' => 'raw',
                'label' => 'Команда',
                'value' => function (Team $model) {
                    return $model->teamLink('string', true);
                }
            ],
            [
                'attribute' => 'country',
                'contentOptions' => ['class' => 'hidden-xs'],
                'footer' => 'Страна',
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'hidden-xs'],
                'label' => 'Страна',
                'value' => function (Team $model) {
                    return $model->stadium->city->country->countryLink();
                }
            ],
            [
                'attribute' => 'base',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'База',
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'hidden-xs'],
                'label' => 'База',
                'value' => function (Team $model) {
                    return $model->baseUsed() . ' из ' . $model->base->base_slot_max;
                }
            ],
            [
                'attribute' => 'stadium',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'Стадион',
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'hidden-xs'],
                'label' => 'Стадион',
                'value' => function (Team $model) {
                    return Yii::$app->formatter->asInteger($model->stadium->stadium_capacity);
                }
            ],
            [
                'attribute' => 'finance',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'Финансы',
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'hidden-xs'],
                'label' => 'Финансы',
                'value' => function (Team $model) {
                    return FormatHelper::asCurrency($model->team_finance);
                }
            ],
            [
                'attribute' => 'vs',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'Vs',
                'footerOptions' => [
                    'class' => 'hidden-xs',
                    'title' => 'Рейтинг силы команды в длительных соревнованиях',
                ],
                'headerOptions' => [
                    'class' => 'hidden-xs',
                    'title' => 'Рейтинг силы команды в длительных соревнованиях',
                ],
                'label' => 'Vs',
                'value' => function (Team $model) {
                    return $model->team_power_vs;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'ЧЗ',
                'footerOptions' => [
                    'class' => 'hidden-xs',
                    'title' => 'Число заявок',
                ],
                'headerOptions' => [
                    'class' => 'hidden-xs',
                    'title' => 'Число заявок',
                ],
                'label' => 'ЧЗ',
                'value' => function (Team $model) {
                    return count($model->teamRequests);
                }
            ],
        ];
        print GridView::widget([
                                   'columns' => $columns,
                                   'dataProvider' => $dataProvider,
                                   'showFooter' => true,
                               ]
        );
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<?= $this->render('//site/_show-full-table') ?>
