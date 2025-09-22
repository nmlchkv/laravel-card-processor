docker compose up -d --build
composer install
php artisan key:generate
php artisan migrate
php artisan test
