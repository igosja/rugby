<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Achievement;
use common\models\db\National;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var National $national
 * @var View $this
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//national/_national-top-left', ['national' => $national]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <?= $this->render('//national/_national-top-right', ['national' => $national]) ?>
    </div>
</div>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//national/_national-links') ?>
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
                'label' => Yii::t('frontend', 'views.th.season'),
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.season')],
                'value' => static function (Achievement $model) {
                    return $model->season_id;
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.th.tournament'),
                'label' => Yii::t('frontend', 'views.th.tournament'),
                'value' => static function (Achievement $model) {
                    return $model->getTournament();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.achievement.position'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.achievement.position'),
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
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//national/_national-links') ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table') ?>
