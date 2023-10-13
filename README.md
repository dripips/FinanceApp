# FinanceApp - Система учета финансов

FinanceApp - это веб-приложение для учета финансов, созданное на базе PHP и MySQL.

## Описание

FinanceApp позволяет пользователям вести учет доходов и расходов, просматривать статистику за разные месяцы и управлять своими финансами. Это полезное средство для отслеживания финансов.

## Требования

Для работы с FinanceApp вам потребуется:

- **Web-сервер** (например, Apache).
- **PHP** версии 7.0 и выше.
- **MySQL** базу данных.

## Установка

1. Клонируйте репозиторий на ваш сервер.

```shell
git clone https://github.com/dripips/FinanceApp.git
```

2. Создайте базу данных MySQL:

```SQL
CREATE DATABASE financeapp;
```
3. Создайте таблицу users для хранения пользователей:

```SQL
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `username` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
```
4. Создайте таблицу incomes для  хранения доходов:

```SQL
CREATE TABLE IF NOT EXISTS `incomes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` datetime NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;
```

5. Создайте таблицу expenses для хранения расходов:

```SQL
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` datetime NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
```

6. Внесите необходимые настройки базы данных в файл `config/database.php`.

```php
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';
```

7. Запустите веб-приложение, перейдя по URL вашего сервера.

## Использование

- **Регистрация и вход в систему:** Перед началом использования FinanceApp необходимо зарегистрироваться и войти в систему.

- **Добавление транзакции:** Вы можете добавлять транзакции в соответствии с их типом (доход или расход) и суммой.

- **Просмотр статистики:** FinanceApp предоставляет статистику доходов и расходов за текущий месяц. Вы также можете просматривать статистику за другие месяцы с помощью кнопок "←" и "→".

- **Просмотр списка транзакций:** Ваши транзакции будут отображаться в виде списка на странице.

## Лицензия

Этот проект распространяется под лицензией MIT. См. [LICENSE](LICENSE) для получения дополнительной информации.

## Авторы

- [DRIP](https://github.com/dripips)

## P.S.

- Скрипт можно дополнить описанием дохода и затрат.
