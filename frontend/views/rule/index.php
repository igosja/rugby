<?php

use common\models\db\Rule;
use yii\helpers\Html;

/**
 * @var Rule[] $ruleArray
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <h1>Правила</h1>
            </div>
        </div>
        <?= Html::beginForm(['search'], 'get', ['class' => 'form-inline text-center']) ?>
        <?= Html::textInput('q', Yii::$app->request->get('q'), ['class' => 'form-control form-small']) ?>
        <?= Html::submitButton('Поиск', ['class' => 'btn']) ?>
        <?= Html::endForm() ?>
        <ul>
            <?php
            foreach ($ruleArray as $rule) : ?>
                <li><?= Html::a($rule->rule_title, ['view', 'id' => $rule->rule_id]) ?></li>
            <?php
            endforeach ?>
        </ul>
    </div>
</div>