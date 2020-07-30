<b>Проект SimpleTODOList</b>

<b>Стек:</b><br />
php 7.4<br />
laravel 7.0<br />
mysql<br />

<b>Устанка и настройка</b><br />
    1. После клонирования репозитория пропишите свои настройки в файл .env<br />
    2. Запустите команду php composer.phar install<br />
    3. При необходимости настройте права доступа к директориям<br />
    4. Запустите команду php artisan migrate для создания таблиц в базе даннх<br />
    5. Запустите команду php artisan db:seed для создания тестовых данных в базе данных<br />
    6. Запустите команду php artisan queue:work для запуска очередей<br />
    
<b>Тестовые данный для входа:</b><br />
    email: admin@site.ru<br />
    password: admin<br />
    
<b>Змечание:</b><br />
    <p>При регистрации нового пользователя необходимо указывать реальный email, т.к. при регистрации на него будет отправлена ссылка для активации доступа. Также необходимо, чтобы на сервере был установлен пакет netsend.</p>
