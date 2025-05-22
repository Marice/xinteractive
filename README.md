# Order Management API

## Installatie & Initialisatie

### 1. Vereisten

- PHP 8.2+
- Composer
- MySQL/MariaDB

---

### 2. Project opzetten

```bash
git clone https://github.com/Marice/xinteractive
cd xinteractive
composer install
```

configureer de `.env`:
Pas de volgende variabele aan in `.env` zodat mariadb connectie kan maken:
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

De fixtures creëren standaard:
- 1 klant (Marice Lamain) -> met vaste id, om het even makkelijk te houden
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

Swagger /Open Api is beschikbaar op:

```
http://localhost:8000/api/doc.json
```

### Authenticatie

Alle endpoints vereisen een geldige Bearer token in de Authorization header:
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
- `tests` – PhpUnit tests

---

### 5. Applicatie gebruiken
in de /docs map zit een postman collectie die ingeladen kan worden om de verschillende endpoints te gebruiken 

### 6. Verbeterpunten (niet aan toegekomen wegens tijdslimiet)
- Een daadwerkelijke UI in React / Angular / Vue, die mooi aansluit op deze backend
- Meer test coverage
- PhpStan met een schone baseline op level 10
- Als een order archived is, de stock weer herstellen
- Verbeterde CI/CD pipeline
- Dto voor OrderItems


