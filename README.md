# Cryptocurrency API

## Стек технологий

- PHP 8.2
- Yii2 Framework
- PostgreSQL
- Docker
- PHPUnit для тестирования

## Запуск проекта в Docker

### 1. Установка Docker и Docker Compose

Убедитесь, что у вас установлены [Docker](https://www.docker.com/get-started) и [Docker Compose](https://docs.docker.com/compose/install/).

### 2. Клонирование репозитория

Клонируйте репозиторий с проектом:

```bash
git clone https://github.com/hapcoun/cryptocalc.git
cd cryptocalc
```

### 3. Запустите контейнеры с помощью команды:

```bash
docker-compose up -d
```

### 4. Выполните миграции для создания необходимых таблиц в базе данных:

```bash
docker-compose exec php yii migrate
```

## Методы API

**Получение списка криптовалют**

GET /cryptocurrencies

Ответ
```json
{
   "pagination": {
      "totalCount": 200,
      "pageSize": 100,
      "page": 1,
      "pageCount": 2
   },
   "items": [
      {"id": 1, "symbol": "BTC", "name": "Bitcoin", "price_usd": 50000},
      {"id": 2, "symbol": "ETH", "name": "Ethereum", "price_usd": 3000}
   ]
}
```

**Получение информации о криптовалюте**

GET /cryptocurrencies/{symbol}

Ответ
```json
{
  "id": 1,
  "symbol": "BTC",
  "name": "Bitcoin",
  "price_usd": 50000
}
```

**Расчет стоимости криптовалюты**

POST /cryptocurrencies/calculate

Тело запроса
```json
{
   "symbol": "BTC",
   "amount": 2,
   "currency": "USD"
}
```

Ответ
```json
{
  "symbol": "BTC",
  "amount": 2,
  "currency": "USD"
}
```

**Обновление курсов криптовалют**

PUT /cryptocurrencies/update

Ответ
```json
{
  "status": "success"
}
```