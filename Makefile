up: docker-up
down: docker-down
restart: down up

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-build:
	docker-compose build --pull

app-composer-install:
	docker exec -i cargo_php composer install

app-analyze:
	docker-compose run app composer phpstan

app-jwt:
	docker-compose run app php bin/console lexik:jwt:generate-keypair