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

```bash
git clone https://github.com/Gobozzz/wallet-task.git
cd wallet-task

2. Запустите
composer install

3. Запустите Laravel Sail:
./vendor/bin/sail up -d

4. Выполните миграции
./vendor/bin/sail artisan migrate --seed

## Использование

API доступен по адресу http://localhost (или другой, если настроили по-другому)
Все запросы можно тестировать через Postman

## Тесты

Для запуска тестов используйте:
./vendor/bin/sail artisan test
