<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=fcharts',
    'username' => 'fcharts',
    'password' => 'as5312977',
    'charset' => 'utf8',
    
//    'class' => 'yii\db\Connection',
//    'dsn' => 'mysql:host=localhost;dbname=fcharts',
//    'username' => 'mysql',
//    'password' => 'mysql',
//    'charset' => 'utf8',

     //Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
];
