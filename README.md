## Awesome Skeleton for modern development on PHP 7.4+ (incl. PHP 8)

> Contains quality tested packages, thoughtful structure and everything you need to develop microservices.

[![Build Status](https://circleci.com/gh/sunrise-php/awesome-skeleton.svg?style=shield)](https://circleci.com/gh/sunrise-php/awesome-skeleton)
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

## Cooking

Set up your database connection:

```bash
cp .env.example .env && nano .env
```

Execute a migration:

```bash
php bin/app migrations:migrate --service 'master' --no-interaction
```

## Run CLI

> incl.: Doctrine DBAL, Doctrine ORM, Doctrine Migrations.

```bash
php bin/app
```

## Run via PHP

> listen 0.0.0.0:3000

```bash
composer serve
```

## Run via RoadRunner

> listen 0.0.0.0:3000

Set up your server:

```bash
cp .rr.yml.example .rr.yml && nano .rr.yml
```

Run the server:

```bash
rr -dv serve
```

## Run via Swoole

> Coming soon...

## Run tests

```bash
composer test
```

## Run benchmarks

```bash
composer bench
```

---

## Run routes through cURL

> you may need to change the server address...

#### Home (index)

```bash
curl -X 'GET' 'http://127.0.0.1:3000/'
```

#### OpenAPI document

```bash
curl -X 'GET' 'http://127.0.0.1:3000/openapi'
```

#### Create an entry (example bundle)

```bash
curl -X 'POST' -H 'Content-Type: application/json' -d '{"name": "foo", "slug": "foo"}' 'http://127.0.0.1:3000/api/v1/entry'
```

#### Update an existing entry (example bundle)

> you need to set an existing ID.

```bash
curl -X 'PATCH' -H 'Content-Type: application/json' -d '{"name": "foo", "slug": "foo"}' 'http://127.0.0.1:3000/api/v1/entry/b06fd41d-d131-4bb9-a472-eb583369437c'
```

#### Delete an existing entry (example bundle)

> you need to set an existing ID.

```bash
curl -X 'DELETE' 'http://127.0.0.1:3000/api/v1/entry/b06fd41d-d131-4bb9-a472-eb583369437c'
```

#### Read an existing entry (example bundle)

> you need to set an existing ID.

```bash
curl -X 'GET' 'http://127.0.0.1:3000/api/v1/entry/b06fd41d-d131-4bb9-a472-eb583369437c'
```

#### List of entries (example bundle)

```bash
curl -X 'GET' 'http://127.0.0.1:3000/api/v1/entry'
```

---

## Useful commands

#### Deploy

```bash
bash bin/deploy
```

#### Down

```bash
bash bin/down 'Reason...'
```

#### Up

```bash
bash bin/up
```

#### Generate Systemd unit for RoadRunner

```bash
php bin/app app:roadrunner:generate-systemd-unit > app.service
```

#### Generate OpenApi document

```bash
php bin/app app:openapi:generate-document > openapi.json
```

---

## Used stack

> see composer.json

* https://github.com/PHP-DI/PHP-DI
* https://github.com/Seldaek/monolog
* https://github.com/sunrise-php/http-router
* https://github.com/doctrine/orm
* https://github.com/doctrine/migrations
* https://github.com/symfony/console
* https://github.com/symfony/validator
* https://github.com/justinrainbow/json-schema

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
