<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=db;port=3306;dbname=swim',
    'username' => 'root',
    'password' => 'oyctyBbGs5wVB9',
    'charset' => 'utf8',
    'attributes' => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));",
    ],
    
    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
