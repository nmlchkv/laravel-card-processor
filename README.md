```bash
# Запуск контейнеров
docker compose up -d --build

# Установка зависимостей
composer install

# Генерация ключа приложения
php artisan key:generate

# Применение миграций
php artisan migrate


# Запуск тестов
php artisan test
