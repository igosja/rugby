<?php

// TODO refactor

namespace backend\assets;

use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Class SignInAsset
 * @package backend\assets
 */
class SignInAsset extends AssetBundle
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
        'css/sb-admin-2.css',
    ];

    /**
     * @var array $depends
     */
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
    ];
}
