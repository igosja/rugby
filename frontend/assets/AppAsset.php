<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package frontend\assets
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style.css',
    ];
    public $js = [
        'js/site.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ];
}

