Recurly notification module for ZF2
======================

Recurly notification module provides handler to process Recurly webhooks.

Requirements
------------

* PHP 7.0 or higher

Installation
------------

Recurly notification module only officially supports installation through Composer. For Composer documentation, please refer to
[getcomposer.org](http://getcomposer.org/).

You can install the module from command line:
```sh
$ php composer.phar require riskio/recurly-notification-module:dev-master
```

Alternatively, you can also add manually the dependency in your `composer.json` file:
```json
{
    "require": {
        "riskio/recurly-notification-module": "dev-master"
    }
}
```

Enable the module by adding `Riskio\Recurly\NotificationModule` key to your `application.config.php` file. Customize the module by copy-pasting
the `recurly.notification.global.php.dist` file to your `config/autoload` folder.
