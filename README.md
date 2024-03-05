### Demo code for simple GuestBook application

```
Author: Serey Panchenko, created 04/Mar/2024
```

PDO Class by Brad Traversy , source code https://gist.github.com/bradtraversy/a77931605ba9b7cf3326644e75530464

Простой пример Гостевой книги на php 8.0.

Без авторизации: при первом заходе на страницу выставляется cookie с уникальным значением в md5.

Заглавная страница выводит список тем, в которые можно зайти и оставить сообщение.

Сообщение выводится в формате Пользователь #<user_id>, время сообщения в формате <день-месяц-год часы:минуты>.
