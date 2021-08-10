<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use kartik\select2\Select2;
use yii\helpers\Html;

/**
 * @var array $countryArray
 * @var array $seasonArray
 * @var int $seasonId
 * @var string $tournaments
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1><?= Yii::t('frontend', 'views.tournament.index.h1') ?></h1>
    </div>
</div>
<?= Html::beginForm(null, 'get') ?>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
        <?= Html::label(Yii::t('frontend', 'views.label.season'), 'seasonId') ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <?php

        try {
            print Select2::widget([
                'data' => $seasonArray,
                'id' => 'seasonId',
                'name' => 'seasonId',
                'options' => ['class' => 'submit-on-change'],
                'value' => $seasonId,
            ]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-4"></div>
</div>
<?= Html::endForm() ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $tournaments ?>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table class="table table-bordered table-hover">
            <tr>
                <th colspan="5"><?= Yii::t('frontend', 'views.tournament.index.th.championship') ?></th>
            </tr>
            <?php foreach ($countryArray as $item): ?>
                <tr>
                    <td>
                        <?= Html::a(
                            Html::img('@country12/' . $item['federationId'] . '.png'),
                            ['federation/team', 'id' => $item['federationId']]
                        ) ?>
                        <?= Html::a($item['countryName'], ['federation/team', 'id' => $item['federationId']]) ?>
                    </td>
                    <?php foreach ($item['division'] as $key => $value) : ?>
                        <td class="text-center col-10">
                            <?php if ('-' === $value) : ?>
                                -
                            <?php else: ?>
                                <?= Html::a(
                                    $value,
                                    [
                                        'championship/index',
                                        'federationId' => $item['federationId'],
                                        'divisionId' => $key,
                                        'seasonId' => $seasonId
                                    ]
                                ) ?>
                            <?php endif ?>
                        </td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
            <tr>
                <th colspan="5"><?= Yii::t('frontend', 'views.tournament.index.th.championship') ?></th>
            </tr>
        </table>
    </div>
</div>