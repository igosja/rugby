<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\Site;
use yii\helpers\Html;

/**
 * @var Site $site
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h3 class="page-header">Site version</h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <strong>
            Current version
            <?= $site->version_1 . '.' . $site->version_2 . '.' . $site->version_3 ?>
            dated
            <?= FormatHelper::asDate($site->version_date) ?>
        </strong>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        PHP <?= PHP_VERSION ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ul class="list-inline preview-links">
            <li>
                <?= Html::a('+', ['version', 'id' => 1], ['class' => 'btn btn-default']) ?>
                - Complete or very significant system rewrite
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ul class="list-inline preview-links">
            <li>
                <?= Html::a('+', ['version', 'id' => 2], ['class' => 'btn btn-default']) ?>
                - Adding new large sections
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ul class="list-inline preview-links">
            <li>
                <?= Html::a('+', ['version', 'id' => 3], ['class' => 'btn btn-default']) ?>
                - Adding new functionality on the pages, fixing typos, bugs without changing functionality,
                refactoring of code and queries, displaying additional statistics in tables and graphs
            </li>
        </ul>
    </div>
</div>
