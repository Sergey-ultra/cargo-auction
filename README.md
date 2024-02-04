### 1. Navigate to Server Directory and Build Docker Containers

```bash
docker-compose up -d
```

### 2. Initialize Database and Run Migrations

Create the PostgreSQL database and run the migrations to set up the required tables.

```bash
php bin/console doctrine:database:create
exec php bin/console doctrine:migrations:migrate
```

Run fixtures

```bash
php bin/console doctrine:fixtures:load
```

Load cities to DB
```bash
php bin/console load:cities
```

### 3. Start the WebSocket Server

Run the WebSocket server for real-time functionality. The `-vv` flag enables verbose output.

```bash
php bin/console websocket:server:run -vv
```


