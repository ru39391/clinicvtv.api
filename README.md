# API для управления элементами сайта стоматологической клиники

Коллекция REST API контроллеров для MODX Revolution, построенных на базе фреймворка [ZoomX](https://github.com/sergant210/ZoomX). Предоставляет готовые CRUD-операции для управления ресурсами, изображениями, отзывами, прайс-листами и другими сущностями.

![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue)
![MODX](https://img.shields.io/badge/MODX-2.8%2B-green)
![ZoomX](https://img.shields.io/badge/zoomx-framework-orange)
![License](https://img.shields.io/badge/license-MIT-lightgrey)

## Содержание

- [Требования](#требования)
- [Установка](#установка)
- [Структура проекта](#структура-проекта)
- [Авторизация и права доступа](#авторизация-и-права-доступа)
- [Формат ответа](#формат-ответа)
- [Коды ответов](#коды-ответов)
- [Базовые контроллеры](#базовые-контроллеры)
- [Специализированные контроллеры](#специализированные-контроллеры)
- [Трейты](#трейты)
- [Константы и настройки](#константы-и-настройки)
- [Особенности реализации](#особенности-реализации)
- [Примеры использования](#примеры-использования)
- [Документация API](#документация-api)
- [Changelog](#changelog)
- [Лицензия](#лицензия)

## Требования

- PHP 7.4+
- MODX Revolution 2.8+
- ZoomX Framework
- Компоненты (при необходимости):
  - FormIt (для работы с формами обратной связи)
  - pthumb (для генерации миниатюр)

## Установка

1. Скопируйте директорию `Controllers` в `core/components/ZoomX/src/Controllers/` вашего MODX-проекта.
2. Убедитесь, что автозагрузка классов ZoomX настроена корректно.
3. Настройте маршруты в соответствии с документацией ZoomX.
4. Настройте CORS-источники через `Constants::ALLOWED_ORIGINS`.

## Структура проекта

```
Controllers/
├── Api/
│   ├── Dept/
│   │   └── GetController.php
│   ├── Picture/
│   │   └── GetController.php
│   ├── Team/
│   │   └── GetController.php
│   ├── Example/
│   │   ├── CreateController.php
│   │   ├── GetController.php
│   │   ├── UpdateController.php
│   │   └── DeleteController.php
│   ├── Price/
│   │   ├── CreateController.php
│   │   ├── GetController.php
│   │   ├── UpdateController.php
│   │   └── DeleteController.php
│   ├── Testimonial/
│   │   ├── CreateController.php
│   │   ├── GetController.php
│   │   ├── UpdateController.php
│   │   └── DeleteController.php
│   └── Feedback/
│       └── CreateController.php
├── Common/
│   ├── CommonController.php
│   ├── CommonTrait.php
│   ├── Constants.php
│   ├── CreateController.php
│   ├── DeleteController.php
│   ├── GetController.php
│   ├── UpdateController.php
│   └── RequestParamsTrait.php
├── AppController.php
└── BaseController.php
```

## Авторизация и права доступа

- **Публичные эндпоинты (без авторизации):**
  - Все `GET`-запросы
  - `POST /api/testimonials` (создание отзыва)
  - `POST /api/feedback` (обратная связь)

- **Защищённые эндпоинты (только роль `Administrator` MODX):**
  - Все `POST` / `PATCH` / `DELETE` (кроме публичных выше)

При отсутствии прав возвращается:

```json
{
  "success": true,
  "data": {
    "success": false,
    "message": "Unauthorized"
  },
  "meta": {
    "total_time": "0.0722 s",
    "query_time": "0.0010 s",
    "php_time": "0.0713 s",
    "queries": 3,
    "source": "cache",
    "memory": "10 240 KB"
  }
}
```

## Формат ответа

Все эндпоинты возвращают JSON в единой обёртке:

```json
{
  "success": true,
  "data": {},
  "meta": {
    "total_time": "0.1314 s",
    "query_time": "0.0047 s",
    "php_time": "0.1267 s",
    "queries": 13,
    "source": "cache",
    "memory": "10 240 KB"
  }
}
```

Ошибки приходят в том же формате, но с `data.success = false`:

```json
{
  "success": false,
  "data": {
    "success": false,
    "message": "Unauthorized"
  },
  "meta": {}
}
```

## Коды ответов

| Код | Описание |
|-----|----------|
| 200 | Успешная операция |
| 400 | Ошибка валидации |
| 401 | Не авторизован (требуется роль Administrator) |
| 404 | Ресурс не найден |
| 405 | Метод не разрешён (GET для `{id}`-эндпоинтов) |
| 500 | Внутренняя ошибка сервера |

## Базовые контроллеры

### BaseController

Абстрактный базовый контроллер. Инициализирует `modX` и отключает автозагрузку ресурсов ZoomX для корректной работы API.

```php
abstract class BaseController
{
    protected $modx;

    public function __construct(modX $modx)
    {
        $this->modx = $modx;
        ZoomX()->autoloadResource(false);
    }
}
```

### CommonController

Базовый контроллер для операций создания, обновления и удаления. Содержит метод `handleData()`, который выполняет основную логику и форматирует ответ.

## Специализированные контроллеры

### Api/Dept/GetController

Возвращает список отделов (дочерние ресурсы с `parent = 7`).

**Метод:** `GET`

**Ответ:**

```json
{
  "success": true,
  "data": [
    {
      "id": 17,
      "pagetitle": "Профилактика",
      "desc": "Все мероприятия для профилактики: профилактические осмотры, профессиональная гигиена полости рта и отбеливание.",
      "menuindex": 5,
      "url": "uslugi/profilaktika-zabolevanij-zubov/"
    }
  ],
  "meta": {
    "total_time": "0.0700 s",
    "query_time": "0.0020 s",
    "php_time": "0.0679 s",
    "queries": 9,
    "source": "cache",
    "memory": "12 288 KB"
  }
}
```

### Api/Picture/GetController

Возвращает список изображений из указанной директории с поддержкой пагинации, сортировки, поиска и генерации миниатюр.

**Параметры:**

| Параметр | Тип    | По умолчанию | Описание                              |
|----------|--------|--------------|---------------------------------------|
| all      | int    | 0            | Вернуть все изображения без пагинации |
| thumbs   | int    | 0            | Генерировать миниатюры                |
| cache    | int    | 3600         | Время кеширования в секундах          |
| dir      | string | из настройки | Директория с изображениями            |
| exts     | string | jpg,jpeg,png | Разрешённые расширения                |
| sortby   | string | date         | Поле сортировки (name, date, size, random) |
| page     | int    | 1            | Номер страницы                        |
| perPage  | int    | 10           | Элементов на странице                 |
| search   | string | null         | Поиск по имени файла                  |
| sortdir  | string | DESC         | Направление сортировки                |

**Ответ:**

```json
{
  "success": true,
  "data": {
    "data": {
      "page": 1,
      "perPage": 10,
      "totalCount": 50,
      "totalPages": 5,
      "data": [
        {
          "name": "Poshenkov_otbelivanie_zoom_posle_1_400x0_3c9.jpg",
          "url": "/assets/images/examples/Poshenkov_otbelivanie_zoom_posle_1_400x0_3c9.jpg",
          "size": 31117,
          "size_formatted": "30.39 KB",
          "date": 1781582767,
          "updatedAt": "2026-06-16T07:06:00Z",
          "ext": "jpg",
          "width": 400,
          "height": 240
        }
      ],
      "sortby": "date",
      "sortdir": "DESC",
      "cache": "cached"
    }
  },
  "meta": {
    "total_time": "0.0838 s",
    "query_time": "0.0008 s",
    "php_time": "0.0830 s",
    "queries": 4,
    "source": "cache",
    "memory": "10 240 KB"
  }
}
```

### Api/Team/GetController

Возвращает список сотрудников с информацией об отделах и фотографиях.

**Метод:** `GET`

**Параметры:**

| Параметр | Тип | Описание                              |
|----------|-----|---------------------------------------|
| dept_id  | int | Фильтр по ID отдела                   |

**Ответ:**

```json
{
  "success": true,
  "data": [
    {
      "id": 53,
      "pagetitle": "Павлова Анна Андреевна",
      "introtext": "2022",
      "menuindex": 10,
      "url": "nasha-komanda/pavlova-anna-andreevna",
      "depts_id": [12, 16],
      "depts": "терапевт, хирург-имплантолог",
      "pics": {
        "webp": "/assets/components/phpthumbof/cache/pavlova-anna-andreevna.716b2ddfbee8671b7728c6d25f36b8ac.webp",
        "thumb": "/assets/components/phpthumbof/cache/pavlova-anna-andreevna.9f7569956ed53acb79be167ec4977333.jpg"
      }
    }
  ],
  "meta": {
    "total_time": "0.2096 s",
    "query_time": "0.0077 s",
    "php_time": "0.2019 s",
    "queries": 36,
    "source": "cache",
    "memory": "10 240 KB"
  }
}
```

### Api/Example/*

Полный CRUD для сущности `exampleItem`. Поле `introtext` — computed (только для чтения), формируется PHP на основе связей с ресурсами. Изображения передаются как пути из галереи `/api/pictures`. При `PATCH` без `img_before`/`img_after` старые изображения сохраняются.

- `CreateController` — `POST /api/examples` (требуется роль Administrator)
- `GetController` — `GET /api/examples` (публичный, с фильтрацией по `spec_id`, `dept_id`, `is_hidden`)
- `UpdateController` — `PATCH /api/examples/{id}` (требуется роль Administrator)
- `DeleteController` — `DELETE /api/examples/{id}` (требуется роль Administrator)
- **`GET /api/examples/{id}` не поддерживается** (возвращает `405 Method Not Allowed`)

### Api/Price/*

CRUD для сущности `pricelistItem` с фильтрацией по `dept_id`, `spec_id` (связь через отдельную таблицу) и `is_hidden`. Поле `is_hidden` — флаг отображения на сайте (0/1), не является мягким удалением.

- `CreateController` — `POST /api/pricelist` (Administrator)
- `GetController` — `GET /api/pricelist` (публичный)
- `UpdateController` — `PATCH /api/pricelist/{id}` (Administrator, обязательные поля: `name`, `price`)
- `DeleteController` — `DELETE /api/pricelist/{id}` (Administrator)
- **`GET /api/pricelist/{id}` не поддерживается**

### Api/Testimonial/*

CRUD для сущности `testimonialItem` с фильтрацией по `spec_id`, `spec_ids` (множественный, через запятую), `rating` и `is_hidden`. Поле `introtext` — computed (только для чтения). `spec_id = 0` — валидное значение «без привязки к врачу».

- `CreateController` — `POST /api/testimonials` (**публичный**)
- `GetController` — `GET /api/testimonials` (публичный)
- `UpdateController` — `PATCH /api/testimonials/{id}` (Administrator)
- `DeleteController` — `DELETE /api/testimonials/{id}` (Administrator)
- **`GET /api/testimonials/{id}` не поддерживается**

### Api/Feedback/CreateController

Обработка форм обратной связи (**публичный эндпоинт**). Сохраняет данные в `FormItForm`, определяет IP-адрес, отправляет уведомление через сниппет `sendFeedBackData`.

**Метод:** `POST`

**Пример ответа:**

```json
{
  "success": true,
  "data": {
    "id": 15,
    "form": "form-11",
    "context_key": "web",
    "values": "{\"src\":\"Записаться, блок соц. сетей\",\"pageId\":\"11\",\"pagetitle\":\"Контакты\",\"phone\":\"+7 (989) 900 67 89\",\"name\":\"Олег\"}",
    "ip": "127.0.0.1",
    "date": 1789467069,
    "encrypted": false,
    "encryption_type": 1,
    "hash": "d93db97f4214310414a6b74136eae9d9",
    "success": true
  },
  "meta": {
    "total_time": "0.9044 s",
    "queries": 9
  }
}
```

## Трейты

### CommonTrait

Содержит вспомогательные методы:

- `getInputData()` — получение JSON из тела запроса
- `setResponseHeaders()` — настройка CORS-заголовков
- `setResponseData()` — формирование ответа
- `formatTimestamp()` — форматирование UNIX-timestamp
- `formatDate()` — форматирование даты в ISO 8601
- `handleThums()` — генерация миниатюр через pthumb
- `getPagetitle()` — получение заголовка ресурса
- `formatData()` — нормализация данных перед отдачей

### RequestParamsTrait

Содержит методы для работы с GET-параметрами:

- `getParam()` — получение параметра с дефолтным значением
- `getPaginationParams()` — получение параметров пагинации
- `getValidPaginationParams()` — валидация параметров пагинации

## Константы и настройки

Класс `Constants` содержит:

- `ALLOWED_ORIGINS` — список разрешённых CORS-источников
- `CREATEDON_KEY` — ключ даты создания
- `UPDATEDON_KEY` — ключ даты обновления
- `IS_ACTIVE_KEY` — ключ активности
- `IS_HIDDEN_KEY` — ключ скрытости
- `ID_KEY` — ключ идентификатора
- `NAME_KEY` — ключ имени
- `SPEC_ID_KEY` — ключ специалиста
- `DEPT_ID_KEY` — ключ отделения
- `RATING_KEY` — ключ рейтинга
- `INTROTEXT_KEY` — ключ вводного текста
- `AFTER_PIC_KEY` — ключ изображения "после"
- `BEFORE_PIC_KEY` — ключ изображения "до"
- `COMMON_ERROR` — общее сообщение об ошибке

## Особенности реализации

- **`spec_id = 0`** — валидное значение «без привязки к специалисту».
- **`is_hidden`** — флаг отображения на сайте (0/1), не является мягким удалением.
- **`introtext`** — computed-поле (только для чтения):
  - для примеров и отзывов — ФИО специалиста;
  - для сотрудников — ручное поле (год начала работы).
- **Поле `success` внутри `data`** — особенность бэкенда (наследие фреймворка).
- **`PATCH` вместо `PUT`** — частичное обновление, обязательные поля указаны в спецификации.
- **`GET /api/{entity}/{id}` не поддерживается** для `pricelist`, `examples`, `testimonials` — возвращается `405 Method Not Allowed`.
- **Параметры пагинации** (`page`, `perPage`) приводятся к числу; нечисловые значения → дефолт.
- **`spec_id` / `dept_id`** — нечисловые значения игнорируются.

## Примеры использования

```bash
# Получение списка изображений
curl --request GET \
  --url 'http://localhost:3000/api/pictures?page=1&perPage=20&thumbs=1&sortby=date&sortdir=DESC'

# Создание записи (требуется авторизация Administrator)
curl --request POST \
  --url 'http://localhost:3000/api/pricelist' \
  --header 'Content-Type: application/json' \
  --data '{
    "name": "Профессиональная гигиена полости рта (Air Flow)",
    "price": 3900,
    "dept_id": 17
  }'

# Обновление записи (PATCH, требуется авторизация Administrator)
curl --request PATCH \
  --url 'http://localhost:3000/api/pricelist/47' \
  --header 'Content-Type: application/json' \
  --data '{
    "name": "Лечение среднего кариеса с пломбировкой",
    "price": 6050
  }'

# Удаление записи (требуется авторизация Administrator)
curl --request DELETE \
  --url 'http://localhost:3000/api/pricelist/47'

# Отправка формы обратной связи (публичный)
curl --request POST \
  --url 'http://localhost:3000/api/feedback' \
  --header 'Content-Type: application/json' \
  --data '{
    "form": "form-11",
    "context_key": "web",
    "values": "{\"name\":\"Клиент\",\"phone\":\"+7 (999) 999 88 77\"}"
  }'
```

## Документация API

Полная спецификация доступна в файле `docs/swagger.yaml` (Swagger 2.0).
Для интерактивного просмотра используйте [Swagger Editor](https://editor.swagger.io/)
или разверните Swagger UI на своём сервере.

## Changelog

- **v1.0** — первая версия API:
  - Публичные GET-эндпоинты.
  - CRUD для `pricelist`, `examples`, `testimonials` (PATCH вместо PUT).
  - `GET /api/{entity}/{id}` не поддерживается (`405 Method Not Allowed`).
  - Единый формат ответа с обёрткой `{ success, data, meta }`.
  - Добавлен множественный фильтр `spec_ids` для отзывов.
  - Публичные POST-эндпоинты: `POST /api/testimonials`, `POST /api/feedback`.
  - `is_hidden` — флаг отображения на сайте, не мягкое удаление.
  - `introtext` — computed-поле для примеров и отзывов.

## Лицензия

MIT

---

**Примечание:** Проект использует MODX Revolution и ZoomX. Убедитесь, что все зависимости установлены и настроены перед использованием контроллеров.
