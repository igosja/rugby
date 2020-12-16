<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Achievement;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/**
 * @var ActiveDataProvider $dataProvider
 */

print $this->render('//user/_top');

?>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//user/_user-links') ?>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'С',
                'footerOptions' => ['title' => 'Сезон'],
                'header' => 'С',
                'headerOptions' => ['class' => 'col-5', 'title' => 'Сезон'],
                'value' => static function (Achievement $model) {
                    return $model->season_id;
                }
            ],
            [
                'footer' => 'Команда',
                'format' => 'raw',
                'header' => 'Команда',
                'value' => static function (Achievement $model) {
                    return $model->team_id ? $model->team->getTeamImageLink() : $model->national->nationalLink(true);
                }
            ],
            [
                'footer' => 'Турнир',
                'header' => 'Турнир',
                'value' => static function (Achievement $model) {
                    return $model->getTournament();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Позиция',
                'format' => 'raw',
                'header' => 'Позиция',
                'headerOptions' => ['class' => 'col-10'],
                'value' => static function (Achievement $model) {
                    return $model->getPosition();
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'showFooter' => true,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<?= $this->render('//site/_show-full-table') ?>
