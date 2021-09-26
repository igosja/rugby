<?php

// TODO refactor

namespace backend\assets;

use rmrevin\yii\fontawesome\CdnFreeAssetBundle;
use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Class AppAsset
 * @package backend\assets
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
        'css/metisMenu.css',
        'css/timeline.css',
        'css/sb-admin-2.css',
    ];

    /**
     * @var string[] $js
     */
    public $js = [
        'js/bootstrap.js',
        'js/metisMenu.js',
        'js/sb-admin-2.js',
        'js/admin.js',
    ];

    /**
     * @var string[] $depends
     */
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        CdnFreeAssetBundle::class,
    ];
}
