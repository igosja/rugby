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
    public const STYLE_DOWN_THE_MIDDLE = 2;
    public const STYLE_CHAMPAGNE = 3;
    public const STYLE_FIFTEEN = 4;
    public const STYLE_TEN = 5;

    /**
     * @param string $name
     * @param array $options
     * @return string
     */
    public static function style(string $name, array $options = []): string
    {
        $options['style'] = ['height' => '13px', 'width' => '13px'];
        return self::icon('/img/style/' . $name . '.svg', $options);
    }

    /**
     * @param string $name
     * @param array $options
     * @return string
     */
    public static function icon(string $name, array $options = []): string
    {
        return Html::img($name, $options);
    }
}
