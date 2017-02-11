Yii 2 REST Project Template
============================

This project was branched via the Yii2 Basic Project Template 
and has been configured to be used just as a REST API. It also contains 
a docker-compose configuration to get up and running using docker quickly.
It requires docker-compose >= 1.6 installed.

The template contains the basic features to build a simple api.
It includes all commonly used configurations that would allow you to focus on adding new
features to your application.


DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      docker/             contains the build files to configure docker using fpm and nginx
      mail/               contains view files for e-mails
      models/             contains model classes
      modules/            contains api modules
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 5.4.0.
It also requires rewrites enable whether via nginx or apache2.


INSTALLATION
------------

### Install via Docker

TODO


CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.

TESTING
-------

TODO
Most likely not working at the moment.
