<?php

// TODO refactor

use backend\assets\SignInAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var $this View
 * @var $content string
 */

SignInAsset::register($this);

?>
<?php

// TODO refactor
$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= Url::to('favicon.ico') ?>"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php

    // TODO refactor
    $this->head() ?>
</head>
<body>
<?php

// TODO refactor
$this->beginBody() ?>
<div class="container">
    <?= $content ?>
</div>
<?php

// TODO refactor
$this->endBody() ?>
</body>
</html>
<?php

// TODO refactor
$this->endPage() ?>
