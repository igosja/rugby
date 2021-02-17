<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\User;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Url;

/**
 * @var ActiveDataProvider $dataProvider
 */

print $this->render('//user/_top');

?>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//user/_user-links') ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table">
            <tr>
                <th><?= Yii::t('frontend', 'views.user.referral.th') ?></th>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.user.referral.p.1') ?>
        </p>
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.user.referral.p.2') ?>
        </p>
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.user.referral.p.3') ?>
        </p>
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.user.referral.p.4') ?>
        </p>
        <p class="text-center text-size-1 strong alert info">
            <?= Url::to(['site/index', 'ref' => Yii::$app->user->id], true) ?>
        </p>
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.user.referral.p.5') ?>
        </p>
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.user.referral.p.6') ?>
        </p>
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.user.referral.p.7', ['value' => FormatHelper::asCurrency(1000000)]) ?>
        </p>
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.user.referral.p.8') ?>:
        </p>
        <ul>
            <li><?= Yii::t('frontend', 'views.user.referral.li.1') ?></li>
            <li><?= Yii::t('frontend', 'views.user.referral.li.2') ?></li>
            <li><?= Yii::t('frontend', 'views.user.referral.li.3') ?></li>
        </ul>
        <p class="text-justify strong red">
            <?= Yii::t('frontend', 'views.user.referral.p.9') ?>
        </p>
        <ul class="red">
            <li><?= Yii::t('frontend', 'views.user.referral.li.4') ?></li>
            <li><?= Yii::t('frontend', 'views.user.referral.li.5') ?></li>
        </ul>
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.user.referral.p.10') ?>
        </p>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'footer' => Yii::t('frontend', 'views.user.referral.table.user'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.user.referral.table.user'),
                'value' => static function (User $model) {
                    return $model->getUserLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center hidden-xs'],
                'footer' => Yii::t('frontend', 'views.user.referral.table.visit'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.user.referral.table.visit'),
                'headerOptions' => ['class' => 'col-25 hidden-xs'],
                'value' => static function (User $model) {
                    return $model->lastVisit();
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center hidden-xs'],
                'footer' => Yii::t('frontend', 'views.user.referral.table.register'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'label' => Yii::t('frontend', 'views.user.referral.table.register'),
                'headerOptions' => ['class' => 'col-25 hidden-xs'],
                'value' => static function (User $model) {
                    return FormatHelper::asDateTime($model->date_register);
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
