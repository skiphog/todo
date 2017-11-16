# Задача — создать сервис для ведения TODO−списков.

## Основные возможности сервиса:
1.	вход / регистрация
2.	создание, редактирование, обновление и удаление TODO−списков
3.	просмотр всех списков на одной странице с пагинацией

## Технологии
**Back-end**
- PHP 7.2
- Laravel (REST)
- Mysql 5.7

 **Front-end**
 - Vue.js
 - Uikit

Из-за того, что TODO лист по сути - одностраничник, 
было принято решение использовать именно эти технологии.
Тем не менее, в рамках задания были созданы отдельные страницы.

## Инструкция по развертыванию
### Склонировать репозиторий
> Требуется установленный composer 
```
git clone https://github.com/skiphog/todo.git ./todo
cd todo/
composer install
```
### Настроить права
```
chown -R www-data storage/
chown -R www-data bootstrap/cache/
```
### База данных
> Требуется создать БД

Переименовать .env-example в .env и настроить конфигурацию.
(Подключение к базе данных и т.д.)

### Сгенерировать ключ
```
php artisan key:generate
```
### Настроить веб сервер
для Nginx
```
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

для Apache
```
Options +FollowSymLinks
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
```

### Выполнить миграции для Базы данных
```
php artisan migrate
```
Для заполнения Демо данными, так же нужно выполнить
```
php artisan db:seed
```
Для входа использовать login - **demo**, password - **demo**

**Profit**


## Для разработки

```
npm install
npm run watch
```

- [Контроллеры](https://github.com/skiphog/todo/tree/master/app/Http/Controllers)
- [Модели](https://github.com/skiphog/todo/tree/master/app)
- [Шаблоны (Vue) и js](https://github.com/skiphog/todo/tree/master/resources/assets/js)
