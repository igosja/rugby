<?php

/**
 * @var View $this
 * @var string $content
 */

use yii\web\View;

?>
<?php
$this->beginPage() ?>
<?php
$this->beginBody() ?>
<?= $content ?>
<?php
$this->endBody() ?>
<?php
$this->endPage() ?>
