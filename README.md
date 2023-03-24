## Product Management

API for product management build in Laravel

## Setup & Install

1. Clone this repository
2. Go to directory of downloaded repository
3. Run `composer install` or `composer update` if install occur an error on terminal/cmd
4. Run `cp .env.example .env`
5. Run `php artisan key:generate`
6. Configure `.env` file such as db_name, db_username, etc.
8. Now it can running with command `php artisan serve`

## Setup & Install using sail

1. Clone this repository
2. Go to directory of downloaded repository
3. Run `composer install` or `composer update` if install occur an error on terminal/cmd
4. Run `cp .env.example .env`
5. Run `php artisan key:generate`
6. Configure `.env` file such as db_name, db_username, etc.
7. Run with `./vendor/bin/sail up`

## Testing

1. Test via postman with postman collection `https://github.com/awgst/product-management-api/blob/master/Product%20Management.postman_collection.json`
2. Test via phpunit || via `php artisan test` command
