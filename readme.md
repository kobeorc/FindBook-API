<p>FindBook API</p>

### Routes:

# Без авторизации:
  
## Авторизация
    POST: login
        Params: email(required),passoword(required)
        При успешной авторизации в ответ придет токен для авторизации
        
## Регистрация
    POST: register
        Params: email(required),name(required),password(required),password_confirmation(required)
        При успешной регистрации вернется 201 ответ сервера
    
    POST: register/silent
        Возвращает авторизационный токен для нового гостевого аккаунта
        
    POST: register/key
        Возвращает ключ для регистрации гостей

# Только с авторизацией:
  *нужен заголовок с авторизационным токеном*
## Список книг
    GET: books
        Params: categoriesIds (array), publishersIds (array), authorsIds (array), except_me (boolean)(исключает книги текущего пользователя), book_ids (array)
                latitude && longitude (формата /^[0-9]+\.([0-9]){0,7}$/), (отдельный запрос, без фильтров)
                square_top && square_left && square_bottom && square_left
        Paginate: offset, limit 
        Возвращает список всех книг  
    
    GET: books/{bookId}
        Возвращает книгу по id
        
    GET: search
        Params: search
        Paginate: offset, limit 
        Возвращает список книг. 
        Поиск сейчас по books.name|books.description|authors.full_name|publishers.full_name
            
## Профиль пользователя
    GET: profile
        Вернет текущего пользователя

    POST: profile
        Params: avatar (file),email,name,password,password_confirmation
        Обновление данных пользователя

    GET: profile/inventory
        Вернет список активных книг пользователя

    POST: profile/inventory
        Params: book_id(если обновление книги), book_name, book_description, year, 
                latitude, longitude, author_full_name[](всегда массив), publisher_full_name, categories_ids[](всегда массив), 
                images[](file передаваемый всегда массивом), address
        Добавление книги и обновление

    DELETE: profile/inventory/{bookId}
        Удаляет книгу
        
    DELETE: profile/inventory/{bookId}/images/{imageId}
        Удаляет изображение из книги
    
    GET: profile/inventory/archive
        Возвращает список книг в архиве
    
    GET: profile/inventory/archive/{bookId}
        Возвращает книгу из архива по id
            
    POST: profile/inventory/archive
        Params: book_id
        Добавляет книгу из инвентаря в архив. при повторном добавление одной и тойже книги будет обновляться время добавления

    DELETE: profile/inventory/archive/{bookId}
        Удаляет книгу которая находится в инвентаре из архива. Ставит archived_at false.

    GET: profile/inventory/favorite
        Возвращает список избранного

    POST: profile/inventory/favorite
        Params: book_id
        Добавить книгу в избранное

    DELETE: profile/inventory/favorite/{bookId}
        Удаляет книгу из избранного
        
    GET: users/{userId}/books
        Возвращает список книг пользователя(не заархивированных)
        
## Категории
    GET: categories
        Возвращает список категорий
        
## Провайдеры
    GET: publishers
        Возвращает список издательств
        
## Подписота
    GET: subscribe
        Возвращает список подписок
        
    GET: subscribers
        Возвращает список подписчиков
        
    POST: subscribe
        Params: user_id(required)
        Добавляет в список подписок пользователя
        
    POST: unsubscribe
        Params: user_id(required)
        Удаляет из списка подписок пользователя
# CHAT (In Test)
## Добавление сообшения
    POST: message/sent
        Params: chat_type(private/group/channel) , message_type(text/audio/video/file/image/combined/forward/reply), to (user_id), text(string)
        Добавляет сообщение. Сейчас работает только с chat_type = private, message_type = text 
## Список чатов пользователя
    GET: chats
        Возвращает список доступных чатов пользователя
        
## Список сообщений в чате
    GET: chats/{chatId}/messages
        Возвращает список сообщений в чате. Есть пагинация, аналогичная роуту books/
        
## Поставить статус отправлено
    POST: chats/{chatId}/messages/{messageId}/sent
        Устанавливает статус сообщения в 'sent'. Только для авторов сообщения
        
## Поставить статус прочитано
    POST: chats/{chatId}/messages/{messageId}/read
        Устанавливает статус сообщения в 'read'. Только не для автора сообщения

_Ликбез по чатику_ <br/>
Есть общий список чатов, он должен быть запрашиваться при открытии экрана с чатами. <br/>
Сообщения чата подтягиваются с другого роута. <br/>
После создания сообщения в чате, нужно подписаться на событие chats.{chatId}. <br/>
Каждые Х секунд нужно запрашивать список чатиков, и подписываться на все из них.(дальше сделаем через пуши) <br/>
Сообщение которое прилетает из сокета аналогична той что приходит из роута. <br/>
Нужно обновлять статусы сообщения, сейчас их 3 и там жесткие валидаторы. Поймешь по ошибкам. <br/>
Сейчас используем сторонний сервис pusher.com , ключи в телеге. заводи аккаунт читай доки <br/>


### Commands
## Start push
  php artisan push:test - педалька для теста пушей
