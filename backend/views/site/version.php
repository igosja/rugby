<?php

use common\components\helpers\FormatHelper;
use common\models\db\Site;
use yii\helpers\Html;

/**
 * @var Site $site
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h3 class="page-header">Версия сайта</h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <strong>
            Текущая версия
            <?= $site->site_version_1 . '.' . $site->site_version_2 . '.' . $site->site_version_3 ?>
            от
            <?= FormatHelper::asDate($site->site_version_date) ?>
        </strong>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        PHP <?= phpversion() ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ul class="list-inline preview-links">
            <li>
                <?= Html::a('+', ['site/version', 'id' => 1], ['class' => 'btn btn-default']) ?>
                - Полное или очень существенное переписывание системы
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ul class="list-inline preview-links">
            <li>
                <?= Html::a('+', ['site/version', 'id' => 2], ['class' => 'btn btn-default']) ?>
                - Добавление новых крупных разделов
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ul class="list-inline preview-links">
            <li>
                <?= Html::a('+', ['site/version', 'id' => 3], ['class' => 'btn btn-default']) ?>
                - Добавления нового функционала на страницах, исправление опечаток, багов без изменения функционала,
                рефакторинг кода и запросов, вывод дополнительной стратистики в таблицах и графиках
            </li>
        </ul>
    </div>
</div>
