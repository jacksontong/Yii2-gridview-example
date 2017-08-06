Yii 2 Basic Project Template
============================

Example Project for Medium's post [How to perform sql expression on Yii Gridview](https://medium.com/@jacksontong/how-to-perform-sql-expression-on-yii-gridview-6f75fc6d9a38)

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      docker              contains docker files  
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project that your Web server supports PHP 5.4.0.


INSTALLATION
------------

* Download and install [Docker Comunity Edition](https://www.docker.com/get-docker)
* run `composer install`
* Makes a new file .env on the project's root directory and copy the content from .env-example
* Run this command on the project's root directory `docker-composer up -d` to shutdown run `docker-compose stop`
* Run `docker-compose exec php-fpm php yii migrate` to seed the database
* Access the project by this url http://localhost:NGINX_PORT (NGINX_PORT is defined on .env file), eg: [http://localhost:8080](http://localhost:8080)


CONFIGURATION
-------------

Edit the file `.env` with real data, for example:

```
MYSQL_DATABASE=yii2basic
MYSQL_USER=db_username
MYSQL_PASSWORD=db_password
MYSQL_ROOT_PASSWORD=db_root_password
NGINX_PORT=80 //you need to change to the other port if port 80 is taken
MYSQL_PORT=3306 //you need to change to the other port if port 3306 is taken
```


TESTING
-------

Tests are located in `tests` directory. They are developed with [Codeception PHP Testing Framework](http://codeception.com/).
By default there are 3 test suites:

- `unit`
- `functional`
- `acceptance`

Tests can be executed by running

```
vendor/bin/codecept run
``` 

The command above will execute unit and functional tests. Unit tests are testing the system components, while functional
tests are for testing user interaction. Acceptance tests are disabled by default as they require additional setup since
they perform testing in real browser. 


### Running  acceptance tests

To execute acceptance tests do the following:  

1. Rename `tests/acceptance.suite.yml.example` to `tests/acceptance.suite.yml` to enable suite configuration

2. Replace `codeception/base` package in `composer.json` with `codeception/codeception` to install full featured
   version of Codeception

3. Update dependencies with Composer 

    ```
    composer update  
    ```

4. Download [Selenium Server](http://www.seleniumhq.org/download/) and launch it:

    ```
    java -jar ~/selenium-server-standalone-x.xx.x.jar
    ``` 

5. (Optional) Create `yii2_basic_tests` database and update it by applying migrations if you have them.

   ```
   tests/bin/yii migrate
   ```

   The database configuration can be found at `config/test_db.php`.


6. Start web server:

    ```
    tests/bin/yii serve
    ```

7. Now you can run all available tests

   ```
   # run all available tests
   vendor/bin/codecept run

   # run acceptance tests
   vendor/bin/codecept run acceptance

   # run only unit and functional tests
   vendor/bin/codecept run unit,functional
   ```

### Code coverage support

By default, code coverage is disabled in `codeception.yml` configuration file, you should uncomment needed rows to be able
to collect code coverage. You can run your tests and collect coverage with the following command:

```
#collect coverage for all tests
vendor/bin/codecept run -- --coverage-html --coverage-xml

#collect coverage only for unit tests
vendor/bin/codecept run unit -- --coverage-html --coverage-xml

#collect coverage for unit and functional tests
vendor/bin/codecept run functional,unit -- --coverage-html --coverage-xml
```

You can see code coverage output under the `tests/_output` directory.
