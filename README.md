# Authentication Service

Provides user Authentication and Authorization to the system using a third party provider.

- [Stack](#stack)
- [Installation](#installation)
- [Usage](#usage)

## Stack

* Symfony 4.2
* PHP 7.1
* AWS Cognito

## Installation

Let's clone the repo from github using the following command:

```
$ git clone git@github.com:mookofe/authentication-service.git
```

Next step install Symfony dependencies running:

```
$ cd authentication-service
$ composer install
```

Setup environment variables:

```batch
$ cp .env.dist .env
```

Run tests:

```batch
$ bin/phpunit
```

## Usage:
Run the application using the following command:

```batch
$ php -S localhost:8002 -t public
```

Finally open your browser using the url: `http://localhost:8002/api`