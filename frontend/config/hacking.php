<?php

// TODO refactor

$urls = [
    '.env',
    '.well-known/apple-app-site-association',
    '_ignition/execute-solution',
    '2019/wp-includes/wlwmanifest.xml',
    '2020/wp-includes/wlwmanifest.xml',
    'a2billing/customer/templates/default/footer.tpl',
    'aaa9/index',
    'aab9/index',
    'actuator/health',
    'admin//config.php',
    'ads.txt',
    'api/jsonws/invoke',
    'apple-app-site-association',
    'Autodiscover/Autodiscover.xml',
    'bag2/index',
    'blog/wp-includes/wlwmanifest.xml',
    'c/version.js',
    'cms/wp-includes/wlwmanifest.xml',
    'console',
    'contact/index',
    'ecp/Current/exporttool/microsoft.exchange.ediscovery.exporttool.application',
    'emergency.php',
    'evox/about',
    'flu/403.html',
    'fm.php',
    'HNAP1/index',
    'humans.txt',
    'index.php',
    'libs/js/iframe.js',
    'mifs/.;/services/LogService',
    'news/wp-includes/wlwmanifest.xml',
    'owa',
    'owa/auth/logon.aspx',
    'owa/auth/x.js',
    'pages/createpage-entervariables.action',
    'phpmyadmin',
    'remote/fgt_lang',
    'remote/login',
    'sdk/index',
    'shop/wp-includes/wlwmanifest.xml',
    'site/wp-includes/wlwmanifest.xml',
    'sito/wp-includes/wlwmanifest.xml',
    'solr',
    'stalker_portal/c/version.js',
    'stream/live.php',
    'streaming/clients_live.php',
    'system_api.php',
    't4/index',
    'test/wp-includes/wlwmanifest.xml',
    'text4041631537820/index',
    'vendor/phpunit/phpunit/src/Util/PHP/eval-stdin.php',
    'web/wp-includes/wlwmanifest.xml',
    'website/wp-includes/wlwmanifest.xml',
    'wordpress/wp-includes/wlwmanifest.xml',
    'wp-content/plugins/iva-business-hours-pro/assets/fontello/LICENSE.txt',
    'wp-content/plugins/wp-automatic/js/main-front.js',
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
    $config['components']['urlManager']['rules']['/' . $url] = 'site/hacking';
    $config['components']['urlManager']['rules']['//' . $url] = 'site/hacking';
}

return $config;