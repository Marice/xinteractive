# Order Management API


## Installatie & Initialisatie

### 1. Vereisten

- PHP 8.2+
- Composer
- Symfony CLI (optioneel, maar aanbevolen)
- MySQL/MariaDB of andere door Doctrine ondersteunde database
- Node.js + npm/yarn (optioneel, indien je frontend/documentatie styling wilt uitbreiden)

---

### 2. Project opzetten

```bash
git clone https://github.com/jouw-gebruiker/your-project.git
cd your-project
composer install
```

Kopieer en configureer je `.env`:

```bash
cp .env .env.local
```

Pas de volgende variabelen aan in `.env.local`:

```
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/xinteractive"
```

---

### 3. Database migreren & vullen

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

Fixtures creëren standaard:

- 1 klant (Marice Lamain)
- 2 producten met vaste IDs (1: Laptop, 2: Mobiele telefoon)

---

### 4. Applicatie starten

```bash
symfony serve
# of met PHP
php -S localhost:8000 -t public
```

API draait nu op: `http://localhost:8000/api`

---

## API-documentatie

Swagger UI is beschikbaar op:

```
http://localhost:8000/api/doc
```

### Authenticatie

De meeste endpoints vereisen een geldige Bearer token in de Authorization header:

```
Authorization: Bearer test-token-123
```

---

## Testen

```bash
php bin/phpunit
```

---


## Mappenstructuur

- `src/Controller` – API controllers
- `src/Entity` – Doctrine entiteiten
- `src/Dto` – Data Transfer Objects
- `src/Service` – Businesslogica
- `src/Contract` – Interfaces voor repositories
- `src/Repository` – Doctrine repositories

---

## ✅ TODO / Roadmap

- [x] OpenAPI-documentatie via Nelmio
- [x] Fixtures met vaste IDs
- [x] Repositories via interfaces
- [ ] Unit- en integratietests uitbreiden
- [ ] CI/CD integratie

