# pointsApi
Сервис для работы с точками интереса пользователя

## Установка

* Все команды выполняются из корня проекта

1) Устанавливаем зависимости composer - `time docker run --rm --volume $PWD/app:/app --volume /var/cache/composer:/tmp --user $(id -u):$(id -g) composer:1.10 install --ignore-platform-reqs`

2) Устанавливаем настройку окружения
* В https://github.com/sudo00/pointsApi/blob/master/build/docker-compose.yml#L17 указывается строка "**development**" - режим разработки, "**production**" - режим продутовой среды
* В режиме "**development**" рекомендую раскомментировать volumes https://github.com/sudo00/pointsApi/blob/master/build/docker-compose.yml#L30 (Не понадобится перезапускать контейнер для обновления кода приложения)

3) Собираем образ и запускаем контейнеры - `docker-compose -f build/docker-compose.yml up -d --build`

## Запуск тестов 

* Выполните команду после установки - `docker exec pointapp vendor/bin/codecept run`

## Документация

 После установки можно перейти в документацию по ссылку http://localhost/docs (Доступна только в режиме **development**)
 Генерация токена - Bearer Token = `md5('secret' . date('Ymd'))` https://github.com/sudo00/pointsApi/blob/master/app/models/User.php#33


## Сброс кэша

http://localhost/site/flush?key=adsadsadsad