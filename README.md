### Demo code for simple GuestBook application
version 1.0 

```
Author: Sergey Panchenko, created 04/Mar/2024
```

Простой пример Гостевой книги на php 8.0.
Запись сообщения в базу данных не реализована в этой версии.

Без авторизации: при первом заходе на страницу выставляется cookie с уникальным значением в md5.

Заглавная страница выводит список тем, в которые можно зайти и оставить сообщение.

Сообщение выводится в формате Пользователь #<user_id>, время сообщения в формате <день-месяц-год часы:минуты>.
