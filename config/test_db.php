<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => getenv('DB_DSN_TEST'),
    'username' => getenv('DB_USERNAME'),
    'password' => getenv('DB_PASSWORD'),
    'charset' => 'utf8mb4',
    'tablePrefix' => getenv('DB_TABLE_PREFIX'),
    'enableSchemaCache' => YII_ENV_PROD,
];
