<?php

// TODO refactor

/**
 * @var array $roundArray
 */

use common\components\helpers\ErrorHelper;
use frontend\components\widgets\LinkBar;

try {
    print LinkBar::widget([
        'items' => $roundArray
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
