<?php

/**
 * @var array $divisionArray
 */

use common\components\helpers\ErrorHelper;
use frontend\components\widgets\LinkBar;

try {
    print LinkBar::widget([
        'items' => $divisionArray
    ]);
} catch (Exception $e) {
    ErrorHelper::log($e);
}
