## Installation


```bash
composer install
php artisan key:generate
```

copy .env.example.dev to .env

Change the .env of the database with your DB Connection

Example of the DB Connection
```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5433
DB_DATABASE=petshop-buckhill
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

And then migrate & seed

```bash
php artisan migrate
php artisan db:seed
```

For Running Test Unit
```bash
php artisan test
```