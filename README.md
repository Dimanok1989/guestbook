# GuestBook
**Гостевая книга для Вашего сайта**

Гостевая книга принимает сообщения от посетителей Вашего сайта.
Поля с именем, e-mail и текстом сообщения обязательны к заполнению.

На странице выводится 25 сообщений.
Имеются сотртировки сообщений: по дате добавления, по имени посетителя, по адресу электронной почты автора собщения.

Для установки гостевой книги просто скопируйте содержимое архива в папку вашего сервера.
Отредактируйте файл **.cfg**, указав в нем настрйоки для подключения к базе данных.
Файл .cfg защищен от просмотра и блокируется сервером.

При первом открытие страницы гостевой книги, если сделано все правильно, в базе данных создатся новая таблица.

Чтобы изменить количество сообщений на странице, измените занчение переменной **$lim** в файле _**route.php:141**_
