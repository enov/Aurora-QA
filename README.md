# Aurora-QA

[Aurora](https://github.com/enov/Aurora.git) is a kohana module to manually map
models to database and expose a REST-like interface.

Aurora-QA is a demo application. It also contains PHPUnit tests that covers
Aurora's code.

## Warning

This application is not intended for production. It has only demonstrative and
testing purposes.

## Installation

Please follow standard Kohana 3.2 application installation.

The tests are run towards a "live" database without any object mocking.

A database schema file exists in the /database folder.

You might want to also update

    date_default_timezone_set('Asia/Beirut');

in bootstrap.php according to your timezone.

## Running the tests

Please run the following command from the root folder:

    phpunit

## Userguide

The Kohana Userguide module is enabled, as you might want to take a look at
/guide/aurora