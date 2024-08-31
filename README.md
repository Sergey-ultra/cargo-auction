### 1. Navigate to Server Directory and Build Docker Containers

```bash
docker-compose up -d
```

### 2. Initialize Database and Run Migrations

Create the PostgreSQL database

```bash
php bin/console doctrine:database:create
```
Run the migrations to set up the required tables and run fixtures to fill tables

```bash
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```
Load cities to DB
```bash
php bin/console load:cities
```

Рестарт Postgres
```bash
/etc/init.d/postgresql restart
```

Or restore db dump
```bash
docker exec -i cargo_postgres psql -U postgres -d cargo < cargo.sql
```

### 3. Create JWT keys

```bash
php bin/console lexik:jwt:generate-keypair
```

### 4.WebSocket Server use Centrifugo app



