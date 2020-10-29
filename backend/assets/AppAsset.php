<?php

namespace backend\assets;

use rmrevin\yii\fontawesome\CdnFreeAssetBundle;
use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Main backend application asset bundle.
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
     * @var array $css
     */
    public $css = [
        'css/metisMenu.css',
        'css/sb-admin-2.css',
        'css/morris.css',
    ];

    /**
     * @var array $js
     */
    public $js = [
        'js/metisMenu.js',
        'js/sb-admin-2.js',
        'js/admin.js',
    ];

    /**
     * @var array $depends
     */
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        CdnFreeAssetBundle::class,
    ];
}
