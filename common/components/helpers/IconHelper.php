<?php

// TODO refactor

namespace common\components\helpers;

use yii\helpers\Html;

/**
 * Class IconHelper
 * @package common\components
 */
class IconHelper
{
    /**
     * @param string $name
     * @param array $options
     * @return string
     */
    public static function style(string $name, array $options = []): string
    {
        $options['style'] = ['height' => '13px', 'width' => '16px'];
        return Html::img('/img/style/' . $name . '.svg', $options);
    }
}
