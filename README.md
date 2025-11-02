# Wallet API

Это тестовое приложение Wallet, разработанное на Laravel с использованием Laravel Sail. Проект демонстрирует базовые операции работы с кошельками и транзакциями.

## Описание

Wallet API — это API-совместимый сервис, позволяющий выполнять переводы и просматривать баланс. Реализованы основные функции для работы с финансовыми операциями.

Все операции с финансами сделаны через транзакции.

## Требования

- Docker и Docker Compose (для запуска Laravel Sail)
- Git
- Composer (установлен глобально или через Sail)

## Установка и запуск

1. Клонируйте репозиторий:

git clone https://github.com/Gobozzz/wallet-task.git
cd wallet-task

2. Запустите
composer install

3. Делаем файл .env
cp .env.example .env

4. Генерируем APP_KEY
./vendor/bin/sail artisan key:generate

5. Запустите Laravel Sail:
./vendor/bin/sail up -d

6. Выполните миграции
./vendor/bin/sail artisan migrate --seed

Если на этапе миграций будет ошибка коннекта к базе, то:
./vendor/bin/sail down
docker volume rm $(docker volume ls -q | grep wallet)
./vendor/bin/sail up -d
sleep 60
./vendor/bin/sail artisan migrate --seed

## Использование

API доступен по адресу http://localhost
Все запросы можно тестировать через Postman

## Роуты

Депозит
http://localhost/api/v1/deposit POST
BODY: {
    "user_id": 1,
    "amount": 500.00,
    "comment": "Пополнение через карту"
}

Списание
http://localhost/api/v1/withdraw POST
BODY: {
    "user_id": 1,
    "amount": 500,
    "comment": "Покупка подписки"
}

Перевод
http://localhost/api/v1/transfer POST
BODY: {
    "from_user_id": 1,
    "to_user_id": 2,
    "amount": 150.00,
    "comment": "Перевод другу"
}

Баланс
http://localhost/api/v1/balance/{user_id} GET

## POSTMAN
Прилагаю готовую коллекцию POSTMAN к проекту по ссылке https://github.com/Gobozzz/wallet-task/blob/main/Wallet.postman_collection.json

## Тесты

Для запуска тестов используйте:
./vendor/bin/sail artisan test
