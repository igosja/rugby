<?php

namespace frontend\assets;

use rmrevin\yii\fontawesome\AssetBundle as FontAwesomeAssetBundle;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Class AppAsset
 * @package frontend\assets
 */
class AppAsset extends AssetBundle
{
    /**
     * @var string $basePath
     */
    public $basePath = '@webroot';

    /**
     * @var string $baseUrl
     */
    public $baseUrl = '@web';

    /**
     * @var string[] $css
     */
    public $css = [
        'css/style.css',
    ];

    /**
     * @var string[] $js
     */
    public $js = [
        'js/site.js',
    ];

    /**
     * @var string[] $depends
     */
    public $depends = [
        YiiAsset::class,
        FontAwesomeAssetBundle::class,
    ];
}

