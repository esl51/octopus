# Octopus

A Laravel-Vue SPA starter kit.
Inspired by https://github.com/cretueusebiu/laravel-vue-spa

## Installation

```bash
git clone git@github.com:esl51/octopus.git ./
composer install
php artisan key:generate --ansi
php artisan jwt:secret --force --ansi
mkdir -p storage/app/public/media
php artisan storage:link
cp .env.example .env
```
Edit `.env` and set your database connection details
```bash
php artisan migrate
npm install
```
## Usage

```bash
# Run backend
php artisan serve
```
#### Development

```bash
# Run frontend in development mode
npm run dev
```
#### Build

```bash
# Build frontend for production
npm run build
```
## Test

```bash
# Unit and Feature tests
php artisan test
# Coverage (need xdebug)
XDEBUG_MODE=coverage php artisan test --coverage-html ./coverage
```
