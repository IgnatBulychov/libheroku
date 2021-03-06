## Веселая книга

Электронная книжная полка

## Установка

1) Клонировать репозиторий:

```
git clone https://github.com/IgnatBulychov/elp-back.git
```

2) Установить зависимости PHP:

```
composer install
```

2) Установить зависимости JS:

```
npm install
```

3) Скомпилировать JS:

```
npm run dev
```

4) Создать новый файл конфигурации:

```
cp .env.example .env
```

5) Сгенерировать ключ приложения

```
php artisan key:generate
```

6) Сгенерировать ключ аутентификации

```
php artisan jwt:secret
```

7) Создать переменные окружения или настроить .env файл в соответствии с базой данных. Кроме того, здесь можно указать реквизиты для входа администратора и количество генерируемых моделей для тестирования:

    ADMIN_NAME=[имя администратора]
    ADMIN_EMAIL=[e-mail администратора]
    ADMIN_PASSWORD=[пароль администратора]

    FACTORY_COUNT_TAGS=[количество случайно сгенерированных тегов]
    FACTORY_COUNT_BOOKS=[количество случайно сгенерированных книг]

8) Произвести миграцию:

```
php artisan migrate
```

9) Создать администратора:

```
php artisan db:seed --class=UserSeeder
```

Сгенерировать модели для тестирования:

```
php artisan db:seed --class=BooksSeeder
```

Или добавить администратора и модели для тестирования одной командой:

```
php artisan db:seed
```

10) Создать ссылку в хранилище:

```
php artisan storage link
```