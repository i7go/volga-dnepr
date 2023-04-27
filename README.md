# REST API endpoint /api/aircraft/airport_schedule

## Установка
1. Создать в БД пользователя
1. Выполнить команды:
```bash
composer install
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console air:import ./docs/task
```

## Документация и примеры

Документация Swagger и проверка API: http://127.0.0.1/api/doc

* Пример1: http://127.0.0.1/api/aircraft/airport_schedule?tail=TEST-001&from_date=2023-01-01T22:00&to_date=2023-05-01T00:00&format=json
* Пример2: http://127.0.0.1/api/aircraft/airport_schedule?tail=TEST-002&from_date=2023-01-02T22:00&to_date=2023-04-30T23:59&format=xml