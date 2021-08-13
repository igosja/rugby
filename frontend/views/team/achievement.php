<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Achievement;
use common\models\db\Team;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var Team $team
 * @var View $this
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//team/_team-top-left', ['team' => $team]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <?= $this->render('//team/_team-top-right', ['team' => $team]) ?>
    </div>
</div>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//team/_team-links', ['id' => $team->id]) ?>
    </div>
</div>
<div class="row">
    <?php

    // TODO refactor

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
                'footer' => Yii::t('frontend', 'views.title.place'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.title.place'),
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
        <?= $this->render('//team/_team-links', ['id' => $team->id]) ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table') ?>
