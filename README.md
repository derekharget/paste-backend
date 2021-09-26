# Paste Backend

Backend API for a pastebin clone that was apart of me learning both Laravel (**Lumen) and React


The front end for the website is available here: https://github.com/derekharget/paste-client


## Requirements

- Postgresql is the primary database and is the one I recommend to run.

- You must run PHP 8 for this application as I use certain methods that are only available in that version.

- *** Warning, installation will fail on MySQL. There is a error on generation the foreign constraint between the table and the migration will fail.

## Instalation

- Setup your .env file to the database settings and application URL

- For both Development and production, you need to generate a application key and a JWT key

```
php artisan jwt:secret

php artisan key:generate

```
    
### Development

- I recommend just using PHP's built-in webserver that is available in dev tools, 
```
php artisan serve
```

### Production

- Set the "Access-Control-Allow-Origin" header to your application's domain

- Configure your webserver like most laravel applications to send all requests to /public/index.php
