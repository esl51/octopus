# Octopus

A Laravel-Vue SPA starter kit.
Inspired by https://github.com/cretueusebiu/laravel-vue-spa

## Installation

```bash
composer create-project --prefer-dist esl51/octopus
```
Edit .env and set your database connection details
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
