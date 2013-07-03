# Aurora-QA

[Aurora](https://github.com/enov/Aurora.git) is a kohana module to manually map
models to database and expose a REST-like interface.

This repository is a test application for Aurora and has some PHPUnit tests
covering the module.

## Installation

Please follow standard Kohana 3.2 application installation.

The tests are run towards a "live" database without any object mocking.

A database schema file exists in the /database folder.

## Running the tests

Please run the following command from the root folder:

    phpunit
