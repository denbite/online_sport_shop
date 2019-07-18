### Install for Windows

First of all go to the project folder.
Open cmd.
~~~
cd your/project/path
~~~

Install vendor packages:
~~~
composer install
~~~

Try to update it to last version:
~~~
composer update
~~~

Find folder config/ and create there db-local.php:
~~~
cd config
xcopy db.php db-local.php
~~~

Configure your db-local.php like this:
```php
<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=swim',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
];
```

Come back to the main project folder and apply migrations:
~~~
cd ..
yii migrate --migrationPath=@yii/rbac/migrations
yii migrate
yii migrate --migrationPath=@vendor/devanych/yii2-cart/migrations
~~~


------
**Notes:**

1. If you want to enable yii debugger:
~~~
cd your/project/path
cd web
echo.>.debug
~~~