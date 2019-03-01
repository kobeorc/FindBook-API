<p>FindBook API</p>

Routes:

  Без авторизации:
  
    POST: login
    Params: email(required),passoword(required)
    При успешной авторизации в ответ придет токен для авторизации

    POST: register
    Params: email(required),name(required),password(required),password_confirmation(required)
    При успешной регистрации вернется 201 ответ сервера
    
    POST: register/silent
    Возвращает авторизационный токен для нового гостевого аккаунта

  Только с авторизацией:
  
    GET: profile
    Вернет текущего пользователя

    POST: profile
    Params: avatar(file),email,name
    Обновление данных пользователя

    GET: profile/inventory
    Вернет список активных книг пользователя

    POST: profile/inventory
    Params: book_id(если обновление книги), book_name, book_description, year, latitude, longitude, author_full_name[](всегда массив), publisher_full_name, category_id, images[](file передаваемый всегда массивом?)
    Добавление книги и обновление. Автор пока 1, картинки только добавляются

    GET: profile/inventory/archive
    Возвращает список книг в архиве

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

    GET: categories
    Возвращает список категорий
    
    GET: publishers
    Возвращает список издательств