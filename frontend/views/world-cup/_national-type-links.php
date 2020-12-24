<?php

// TODO refactor

/**
 * @var array $nationalTypeArray
 */


use common\components\helpers\ErrorHelper;
use frontend\components\widgets\LinkBar;

try {
    print LinkBar::widget([
        'items' => $nationalTypeArray
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
