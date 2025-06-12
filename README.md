# SOAP Wallet Service

This project simulates a virtual wallet system using a **SOAP service built in Laravel**, accessed through a **RESTful bridge built in Express (Node.js)**.

## 🔧 Features

### SOAP Service (Laravel)

- `registerClient(document, name, email, phone)`  
  Registers a new user in the system.

- `loadWallet(document, phone, amount)`  
  Recharges the user's wallet balance.

- `makePurchase(document, phone)`  
  Generates a 6-digit confirmation token and session ID, simulating a purchase. Token is sent to the registered email.

- `confirmPayment(session_id, token)`  
  Confirms a purchase using the session ID and token. Deducts 1 unit from the wallet.

- `checkBalance(document, phone)`  
  Returns the current balance of the wallet.

### REST Bridge (Express + Node.js)

Each SOAP method is exposed via a RESTful POST endpoint:

- `POST /wallet/register`
- `POST /wallet/load`
- `POST /wallet/purchase`
- `POST /wallet/confirm`
- `POST /wallet/balance`

The REST service **does not access the database**, it only communicates with the SOAP layer.

---

## 📦 Tech Stack

- **Laravel 12**, **PHP 8.2+**
- **Express (Node.js + TypeScript)**
- **MySQL 8**
- **MailHog** (for local email testing)
- **Docker (optional)**

---

## 🧱 Requirements

- PHP >= 8.2 with SOAP extension enabled
- Composer
- Node.js >= 18
- pnpm (or npm/yarn)
- MySQL
- (Optional) Docker + Docker Compose

---

## 🚀 Installation

### 1. Clone the project

```bash
git clone https://github.com/your-user/soap-wallet.git
cd soap-wallet
```

---

### 2. Set up SOAP service (Laravel)

```bash
cd soap-service

# Install PHP dependencies
composer install

# Environment
cp .env.example .env

# Generate key and migrate
php artisan key:generate
php artisan migrate

# Run server
php artisan serve
```

This will start on `http://localhost:8000`.

---

### 3. Set up REST service (Express + Node.js)

```bash
cd ../rest-service

# Install Node dependencies
pnpm install   # or npm install / yarn install

# Start REST server (on port 3000)
pnpm dev       # or npm run dev

# Environment
cp .env.example .env
```

This will start on `http://localhost:3000`.

---

## 🧪 Testing

You can test the REST endpoints using **Postman** or **cURL**.

### Example: Register client

```http
POST http://localhost:3000/wallet/register
Content-Type: application/json

{
  "document": "12345678",
  "name": "Christian",
  "email": "test@zunida.com",
  "phone": "999999999"
}
```

The response will follow this structure:

```json
{
  "success": true,
  "cod_error": "00",
  "message_error": "Client registered successfully",
  "data": null
}
```

---

## 📂 Folder Structure

```
soap-service/
  └── app/Soap/Handlers/WalletService.php
  └── app/SoapServiceProvider.php

rest-service/
  └── src/controllers/wallet.controller.ts
  └── src/soap/soapClient.ts
```

---

## 📬 Email (Token Delivery)

The `makePurchase` SOAP operation sends a token by email using **Mailhog** or logs it to:

```
storage/logs/laravel.log
```
---

## 🐳 Docker Compose (MySQL Only)

To quickly set up a MySQL database for the Laravel SOAP service, use the following `docker-compose.yml`:

```yaml
version: '3.8'

services:
  mysql:
    image: mysql:8.0
    container_name: wallet-mysql
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: wallet
      MYSQL_USER: wallet_user
      MYSQL_PASSWORD: wallet_pass
    ports:
      - "3306:3306"
    volumes:
      - mysql-data:/var/lib/mysql
    networks:
      - wallet-net

volumes:
  mysql-data:

networks:
  wallet-net:
```

### 💡 Usage

```bash
# Start MySQL
docker compose up -d

# Then configure your Laravel .env as:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wallet
DB_USERNAME=wallet_user
DB_PASSWORD=wallet_pass
```

Then run migrations:

```bash
cd soap-service
php artisan migrate
```

---

## ✅ Running Tests

### ▶️ Real SOAP Integration Tests

These tests use `SoapClient` to make actual HTTP requests to your Laravel SOAP server.

1. In one terminal, run the Laravel server:

```bash
cd soap-service
php artisan serve
```

2. In another terminal, run the tests:

```bash
php artisan test --filter=WalletSoapTest
```

These tests will fail if the SOAP server is not running at `http://localhost:8000`.

---

### 🧪 Mocked SOAP Tests

You can also run faster PHPUnit tests that mock the `SoapClient` and don’t require the SOAP server to be running.

```bash
php artisan test --filter=WalletSoapMockTest
```

These are great for CI and unit testing.

---

### 📈 Code Coverage

To generate a test coverage report (requires Xdebug or phpdbg):

```bash
# Console coverage
php artisan test --coverage

# HTML report
php artisan test --coverage-html storage/coverage
```

Then open the report in your browser:

```
storage/coverage/index.html
```

---

## 🧑‍💻 Author

Developed by Christian Espinoza  
📧 espinoza.c@pucp.edu.pe