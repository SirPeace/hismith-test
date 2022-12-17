# Тестовое задание HiSmith

Задание выполено, используя следующие технологии:
- Symfony 5
- DoctrineORM
- EasyAdmin
- PostgreSQL
- ReactPHP

## Запуск приложения

<br>

Перед выполнением комманд убедитесь, что у вас глобально доступен Symfony CLI.

Установка Symfony CLI: <https://symfony.com/download>

1. Установить зависимости:
```
composer install
```
2. Создать docker-compose файл:
```
symfony console make:docker:database
2
alpine
```
3. Запустить docker контейнер с базой данных:
```
docker-compose up -d
```
4. Мигрировать схему базы данных:
```
symfony console doctrine:migrations:migrate
```
5. Запустить комманду:
```
symfony console app:listen-for-news <?interval>
```
6. Запустить локальный сервер для админ-панели:
```
symfony serve
```

## Критерии

### Парсинг
- Запрос к ресурсу через HttpClient и сохранение ответа в файл
- Парсинг файла средствами библиотеки simple_xml
- Удаление файла
- Сохранение парсированных данных в БД

### Логирование
Логирование по умолчанию Symfony компонентами находится в файле:
```
var/log/dev.log
```
