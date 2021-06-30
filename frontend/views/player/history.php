<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\History;
use common\models\db\Player;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var Player $player
 * @var View $this
 */

print $this->render('//player/_player', ['player' => $player]);

?>
    <div class="row margin-top-small">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= $this->render('//player/_links', ['id' => $player->id]) ?>
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
                    'headerOptions' => ['class' => 'col-3', 'title' => Yii::t('frontend', 'views.title.season')],
                    'label' => Yii::t('frontend', 'views.th.season'),
                    'value' => static function (History $model) {
                        return $model->season_id;
                    }
                ],
                [
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => Yii::t('frontend', 'views.th.date'),
                    'headerOptions' => ['class' => 'col-15'],
                    'label' => Yii::t('frontend', 'views.th.date'),
                    'value' => static function (History $model) {
                        return FormatHelper::asDate($model->date);
                    }
                ],
                [
                    'footer' => Yii::t('frontend', 'views.th.event'),
                    'format' => 'raw',
                    'label' => Yii::t('frontend', 'views.th.event'),
                    'value' => static function (History $model) {
                        return $model->text();
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
            <?= $this->render('//player/_links', ['id' => $player->id]) ?>
        </div>
    </div>
<?= $this->render('//site/_show-full-table') ?>
