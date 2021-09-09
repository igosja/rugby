<?php

// TODO refactor

$urls = [
    '.env',
    '_ignition/execute-solution',
    '2019/wp-includes/wlwmanifest.xml',
    '2020/wp-includes/wlwmanifest.xml',
    'actuator/health',
    'admin//config.php',
    'ads.txt',
    'api/jsonws/invoke',
    'Autodiscover/Autodiscover.xml',
    'blog/wp-includes/wlwmanifest.xml',
    'cms/wp-includes/wlwmanifest.xml',
    'console',
    'ecp/Current/exporttool/microsoft.exchange.ediscovery.exporttool.application',
    'emergency.php',
    'humans.txt',
    'index.php',
    '//libs/js/iframe.js',
    'mifs/.;/services/LogService',
    'news/wp-includes/wlwmanifest.xml',
    'owa',
    'owa/auth/logon.aspx',
    'owa/auth/x.js',
    'shop/wp-includes/wlwmanifest.xml',
    'site/wp-includes/wlwmanifest.xml',
    'sito/wp-includes/wlwmanifest.xml',
    'solr',
    'test/wp-includes/wlwmanifest.xml',
    'vendor/phpunit/phpunit/src/Util/PHP/eval-stdin.php',
    'web/wp-includes/wlwmanifest.xml',
    'website/wp-includes/wlwmanifest.xml',
    'wordpress/wp-includes/wlwmanifest.xml',
    'wp-content/plugins/iva-business-hours-pro/assets/fontello/LICENSE.txt',
    'wp-content/plugins/wp-file-manager/readme.txt',
    'wp-includes/wlwmanifest.xml',
    'wp-login.php',
    'wp/wp-includes/wlwmanifest.xml',
    'wp1/wp-includes/wlwmanifest.xml',
    'wp2/wp-includes/wlwmanifest.xml',
    'xmlrpc.php',
];

$config = [];

foreach ($urls as $url) {
    $config['components']['urlManager']['rules'][$url] = 'site/hacking';
}

return $config;
