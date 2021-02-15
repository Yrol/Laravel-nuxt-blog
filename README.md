## Laravel Nuxt Blog

A blog application developed using Laravel.

## Features

- Basic CRUD operation for blog posts.
- User management (Register, Login, Account reset and Account activation - using mailtrap)

## Design Pattern

- Repository

## Tech stack

- Nginx
- PHP
- MySQL
- Composer
- NPM
- Artisan

## Prerequisites

## Packages

- Auth controllers Laravel 7 (needs to be generated manually). Used extensively within the project to Authenticate users, send verification emails & etc.

```sh
composer require laravel/ui
php artisan ui:controllers
```

## Launching the Laravel project

- Make sure the MySQL credentials are matching in `./src/.env` and `docker-compose.yml` files.

- Build and run the Docker dev environment by executing the following bash file in the `root`.

```sh
./deploy-local
```

- Launch the Laravel project at `http://localhost:8080/` in your preferred browser.

# Composer and NPM

- Composer install and update

```sh
./composer-install
./composer-update
```

- NPM install and run (dev)

```sh
./npm-install
./npm-run-dev
```

# Executing all test cases

```sh
./application-test
```

# Artisan

- Artisan migrate

```sh
./artisan-migrate
```

# Bash command map

- **./deploy-local** - `docker-compose build && docker-compose up -d`
- **./composer-install** - `docker-compose run --rm composer install`
- **./composer-update** - `docker-compose run --rm composer update`
- **./npm-install** - `docker-compose run --rm npm install`
- **./npm-run-dev** - `docker-compose run --rm npm run dev`
- **./artisan-migrate** - `docker-compose run --rm artisan migrate`

Additionally you may include more commands to suite your needs.

# Containers and Ports

- **nginx** - `:8080`
- **mysql** - `:3306`
- **php** - `:9000`
- **npm**
- **artisan**
