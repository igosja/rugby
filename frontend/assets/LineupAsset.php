<?php

// TODO refactor

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class LineupAsset
 * @package frontend\assets
 */
class LineupAsset extends AssetBundle
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
     * @var array $js
     */
    public $js = [
        'js/lineup.js',
    ];

    /**
     * @var array $depends
     */
    public $depends = [
        AppAsset::class,
    ];
}
