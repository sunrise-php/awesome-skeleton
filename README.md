## Awesome Skeleton for modern development on PHP 7.3+

[![Build Status](https://scrutinizer-ci.com/g/sunrise-php/awesome-skeleton/badges/build.png?b=master)](https://scrutinizer-ci.com/g/sunrise-php/awesome-skeleton/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/sunrise-php/awesome-skeleton/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/sunrise-php/awesome-skeleton/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sunrise-php/awesome-skeleton/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sunrise-php/awesome-skeleton/?branch=master)
[![Total Downloads](https://poser.pugx.org/sunrise/awesome-skeleton/downloads?format=flat)](https://packagist.org/packages/sunrise/awesome-skeleton)
[![Latest Stable Version](https://poser.pugx.org/sunrise/awesome-skeleton/v/stable?format=flat)](https://packagist.org/packages/sunrise/awesome-skeleton)
[![License](https://poser.pugx.org/sunrise/awesome-skeleton/license?format=flat)](https://packagist.org/packages/sunrise/awesome-skeleton)

---

## Installation

```bash
composer create-project 'sunrise/awesome-skeleton:^3.0' app
```

## Run CLI (including Doctrine DBAL, Doctrine ORM, Doctrine Migrations)

```bash
php bin/app
```

## Run via PHP (listen 0.0.0.0:3000)

```bash
composer serve
```

## Run via RoadRunner (listen 0.0.0.0:3000)

```bash
rr -dv serve
```

## Run tests

```bash
composer test
```

## Run benchmarks

```bash
composer bench
```

---

## Useful commands

#### Generate Systemd unit file for RoadRunner 

```bash
php bin/app app:roadrunner:generate-systemd-unit > app.service
```

#### Generate OpenApi document

```bash
php bin/app app:openapi:generate-documentation --pretty > openapi.json
```

---

## Used stack

* https://github.com/PHP-DI/PHP-DI
* https://github.com/Seldaek/monolog
* https://github.com/sunrise-php/http-router
* https://github.com/doctrine/orm
* https://github.com/doctrine/migrations
* https://github.com/symfony/console
* https://github.com/symfony/validator
* https://github.com/neomerx/cors-psr7
* https://github.com/justinrainbow/json-schema
* https://github.com/filp/whoops
* https://github.com/twigphp/Twig

## Used technology

#### RoadRunner

* https://roadrunner.dev/
* https://github.com/spiral/roadrunner

#### OpenApi (Swagger) specification

* https://swagger.io/docs/specification/about/
* https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md

#### Json Schema specification

* https://json-schema.org/specification.html

---

## It may be useful to you

#### Awesome middlewares for your application

* https://github.com/middlewares/awesome-psr15-middlewares

---

with :heart: for you
