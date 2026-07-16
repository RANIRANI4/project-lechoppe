# L'Échoppe

A click-and-collect marketplace connecting local producers with nearby consumers. Producers manage their own shops and products; anonymous buyers browse nearby shops, build a cart, and pay on pickup.

## Overview

L'Échoppe lets local producers publish a shop with its products and pickup slots, and lets visitors find shops near them, add products to a cart, and place an order collected and paid for on-site. There is no online payment: the order is a reservation honoured at pickup.

Key features:

- **Producer space** — full CRUD for shops, products, and sell slots, restricted to the owning producer.
- **Geolocated search** — visitors search shops by proximity, using distance computed directly in MySQL.
- **Session-based cart** — anonymous visitors build a cart with no account required.
- **Order flow** — cart validation creates an order with a price snapshot, then producers track its status (to prepare, ready, collected).

## Tech stack

| Layer | Technology |
|-------|------------|
| Language | PHP 8.5 |
| Framework | Symfony 7.4 |
| ORM | Doctrine ORM |
| Database (relational) | MySQL |
| Cache (NoSQL, key-value) | Redis, via the Predis PHP client |
| Templating | Twig |
| Front-end | Bootstrap 5.3, SCSS compiled with Webpack Encore |
| Geocoding | Nominatim (OpenStreetMap) |
| Web server (production) | Nginx + PHP-FPM |

## Requirements

- PHP 8.5 (with the extensions required by Symfony 7.4)
- Composer
- Node.js and npm
- MySQL 8
- Redis server

> Redis access uses **Predis**, a pure-PHP client declared in `composer.json`, so no PHP Redis extension needs to be compiled on the host. Only the `redis-server` service must be available.

## Getting started (local)

Clone the repository and install dependencies:

```bash
git clone <your-repo-url> lechoppe
cd lechoppe

composer install
npm install
npm run build
```

Create a local environment file `.env.local` at the project root:

```dotenv
APP_ENV=dev
APP_SECRET=<your-secret>
DATABASE_URL="mysql://<user>:<password>@127.0.0.1:3306/<db_name>?serverVersion=8.0&charset=utf8mb4"
REDIS_URL=redis://localhost:6379
```

Create the database and run migrations:

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

Optionally load demo data:

```bash
php bin/console doctrine:fixtures:load
```

Start the development server (Symfony CLI):

```bash
symfony server:start
```

For live asset rebuilds during development, run Webpack Encore in watch mode in a separate terminal:

```bash
npm run watch
```

## Architecture

### Domain entities

- **User** — a producer who owns shops.
- **Shop** — a producer's storefront, with an address geocoded to latitude/longitude.
- **Product** — an item sold by a shop.
- **SellSlot** — a pickup time slot offered by a shop.
- **Cart** — held in the HTTP session (not persisted).
- **CustomerOrder / CustomerOrderItem** — a placed order and its lines, each line storing the unit price at purchase time.
- **Consumer** — the buyer's contact details captured at checkout.

Soft deletes are handled through an `EnumState` (Active / Inactive) rather than physical deletion, preserving referential integrity with past orders. Order progress is tracked with an `EnumOrderStatus` (ToPrepare / Ready / Collected).

### Data access

- **Relational access** goes through Doctrine ORM. Entity mapping plus repositories form the data-access layer; reads use repository methods and the QueryBuilder, writes use the EntityManager (`persist` / `flush`). All ORM queries are parameterised, guarding against SQL injection.
- **Geospatial search** uses a single native SQL query in `ShopRepository::findByDistance()`, computing distance with MySQL's `ST_Distance_Sphere` and hydrating results back into `Shop` objects via a `ResultSetMappingBuilder`. Parameters remain bound (`:lat`, `:lng`, `:radius`).
- **NoSQL cache** — `GeocoderService` caches geocoding results in Redis (key-value store) through Symfony's Cache abstraction, keyed by an md5 hash of the address with a 30-day TTL. This avoids repeated calls to the rate-limited Nominatim API.

### Cart

`CartService` isolates all cart logic. The cart is stored in the session as a compact `productId => quantity` map; product objects are reloaded from the database on demand so prices and availability are always current. Totals are always recomputed server-side from database prices, never trusted from the client.

### Checkout

On checkout, the cart is turned into a `CustomerOrder` with one `CustomerOrderItem` per line. Each line records the product's current price via `setUnitPriceAtPurchase()` — a **price snapshot** — so later price changes do not alter past orders. The order and all its lines are persisted in a single atomic `flush()`.

## Security

- Producer passwords are hashed with Symfony's automatic password hasher.
- Route access is restricted via `access_control` in `security.yaml`, complemented by ownership checks in controllers.
- CSRF protection is applied on forms and on cart write actions.
- All inputs are validated through Symfony forms before persistence.
- Uploaded shop images are validated by real MIME type (whitelist of jpeg / png / webp), not by extension, and stored under a server-generated filename.
- Secrets live in `.env.local`, which is not committed.

## Project scripts

```bash
# Build assets for production
npm run build

# Rebuild assets on change (development)
npm run watch

# Run the test suite
php bin/phpunit
```

## Deployment (summary)

Production runs on Ubuntu with Nginx, PHP-FPM, MySQL, and Redis. Deployment provisions the server packages (including `redis-server`), installs dependencies with `composer install --no-dev --optimize-autoloader`, builds assets, configures `.env.local` (including `REDIS_URL`), applies migrations, and points an Nginx server block at the `public/` directory. See the project dossier for the full step-by-step procedure.

## License

Proprietary — all rights reserved.
