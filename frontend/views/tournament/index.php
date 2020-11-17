<?php

// TODO refactor

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
        <h1>Турниры</h1>
    </div>
</div>
<?= Html::beginForm(null, 'get') ?>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
        <?= Html::label('Сезон', 'seasonId') ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <?= Html::dropDownList(
            'seasonId',
            $seasonId,
            $seasonArray,
            ['class' => 'form-control submit-on-change', 'id' => 'seasonId']
        ) ?>
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
                <th colspan="5">Национальные чемпионаты</th>
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
                <th colspan="5">Национальные чемпионаты</th>
            </tr>
        </table>
    </div>
</div>