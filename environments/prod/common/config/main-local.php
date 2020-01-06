<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'charset' => 'utf8',
            'dsn' => 'mysql:host=localhost;dbname=vrol',
            'enableSchemaCache' => true,
            'password' => 'JGvjBoCmVhLVzKDnYiH6',
            'schemaCacheDuration' => 3600,
            'username' => 'vrol',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
