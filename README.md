# Lesara ERP Test project

As a member of Lesara ERP team I want to be able to get actual price of products to provide money accurate documents for customs.

Our operational branch in China specifies product price in yuan (¥, CNY) and Berlin HQ uses euro (€, EUR) as currency.

## Before you start

1. Technical requirements
    * Linux/Mac/Windows machine with `docker` and `docker-compose` installed
    * **If you don't want to use docker do following**:
        * Install WEB server, MySQL server, PHP 7.1+ and Composer
        * Configure - WEB server should point to `public/index.php`
        * Configure - (**only after completion of point 2**) `.env` part of `DATABASE_URL` with local set-up credentials
        * Run `composer install`
        * If something doesn't work, follow [this link](https://symfony.com/doc/current/setup/web_server_configuration.html) for more details
2. Copy `.env.dist` to `.env`
3. (optional) Apply configuration for `DOCKER_*` values in `.env`, if required (e.g. Web server port in use, change `DOCKER_NGINX_LOCAL_PORT` to preferred value)
4. Run `docker-compose up -d --build`
5. Run `docker-compose exec php-fpm composer install`
6. Open `127.0.0.1:80` to see, if everything is up & running

## Your tasks
- create a database schema to save the necessary information
- use free [fixer.io](https://fixer.io) JSON API to get conversion rates
- create an API endpoint to save the products, payload should look like this:

```yaml
{
  "sku": "LES-123-s-green",
  "name": "Cotton t-shirt, S, green",
  "price": {
    "value": 170.87,
    "currency": "CNY"
  }
}
```
- create and API endpoint to list all saved products with following parameters:
    - `from` (optional): return only products that were saved after that date and time (format `Y-m-d H:i:s`)
    - `to` (optional): return only products that were saved before that date and time (format `Y-m-d H:i:s`)
    - `currency` (optional, default `EUR`): in which currency the price should be returned (valid options: `EUR`, `CNY`)
- create a simple view in the application with all saved products
