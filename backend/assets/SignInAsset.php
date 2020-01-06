<?php

namespace backend\assets;

use yii\web\AssetBundle;

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
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
