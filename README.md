<a href="https://codeclimate.com/github/Teol55/Billetterie_Louvre/maintainability"><img src="https://api.codeclimate.com/v1/badges/4666bac67ab5f4339c27/maintainability" /></a>

**`Billetterie du Louvre`**

It's student project for OpenClassroom with Symfony 4 framework

**Requirements**

PHP 7.1.3 or higher;
PDO-SQLite PHP extension enabled;
and the usual Symfony application requirements.

**Installation**

Install the Symfony client binary and run this command:

$ symfony new --demo my_project
Alternatively, you can use Composer:

$ composer create-project symfony/symfony-demo my_project
Usage
There's no need to configure anything to run the application. If you have installed the Symfony client binary, run this command to run the built-in web server and access the application in your browser at http://localhost:8000:

$ cd my_project/
$ symfony serve
If you don't have the Symfony client installed, run php bin/console server:run. Alternatively, you can configure a web server like Nginx or Apache to run the application.

**Tests**

Execute this command to run tests:

$ cd my_project/
$ vendor/bin/simple-phpunit