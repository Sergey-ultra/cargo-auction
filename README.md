### 1. Navigate to Server Directory and Build Docker Containers

```bash
cd packages/server
docker-compose up -d
```

### 2. Initialize Database and Run Migrations

Create the PostgreSQL database and run the migrations to set up the required tables.

```bash
docker-compose exec php bin/console doctrine:database:create
docker-compose exec php bin/console doctrine:migrations:migrate
```

### 3. Start the WebSocket Server

Run the WebSocket server for real-time functionality. The `-vv` flag enables verbose output.

```bash
docker-compose exec php bin/console websocket:server:run -vv
```
