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
                'footer' => Yii::t('frontend', 'views.th.season'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.season')],
                'header' => Yii::t('frontend', 'views.th.season'),
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.season')],
                'value' => static function (Achievement $model) {
                    return $model->season_id;
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.th.team'),
                'format' => 'raw',
                'header' => Yii::t('frontend', 'views.th.team'),
                'value' => static function (Achievement $model) {
                    return $model->team_id ? $model->team->getTeamImageLink() : $model->national->nationalLink(true);
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.th.tournament'),
                'header' => Yii::t('frontend', 'views.th.tournament'),
                'value' => static function (Achievement $model) {
                    return $model->getTournament();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.achievement.position'),
                'format' => 'raw',
                'header' => Yii::t('frontend', 'views.th.achievement.position'),
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
