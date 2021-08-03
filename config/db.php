<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=fcharts',
    'username' => '',
    'password' => '',
    'charset' => 'utf8',

     //Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
];
