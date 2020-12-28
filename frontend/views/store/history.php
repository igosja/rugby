<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Money;
use common\models\db\User;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/**
 * @var ActiveDataProvider $dataProvider
 * @var User $user
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1>История платежей</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong">
        <p class="text-center">Ваш счёт - <?= Yii::$app->formatter->asDecimal($user->money, 2) ?></p>
    </div>
</div>
<div class="row margin-top-small text-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//store/_links') ?>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Дата',
                'headerOptions' => ['class' => 'col-15'],
                'label' => 'Дата',
                'value' => static function (Money $model) {
                    return FormatHelper::asDateTime($model->date);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-right'],
                'footer' => 'Было',
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'col-10 hidden-xs'],
                'label' => 'Было',
                'value' => static function (Money $model) {
                    return $model->value_before;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => '+/-',
                'headerOptions' => ['class' => 'col-10'],
                'label' => '+/-',
                'value' => static function (Money $model) {
                    return $model->value;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-right'],
                'footer' => 'Стало',
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'col-10 hidden-xs'],
                'label' => 'Стало',
                'value' => static function (Money $model) {
                    return $model->value_after;
                }
            ],
            [
                'footer' => 'Комментарий',
                'label' => 'Комментарий',
                'value' => static function (Money $model) {
                    return $model->moneyText->text;
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
