<?php

use common\components\helpers\FormatHelper;
use common\models\db\Rule;
use yii\helpers\Html;

/**
 * @var Rule $rule
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <h1><?= $rule->rule_title ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center text-size-3">
                <?= FormatHelper::asDateTime($rule->rule_date) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= $rule->rule_text ?>
            </div>
        </div>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::a('Назад', ['index']) ?>
            </div>
        </div>
    </div>
</div>