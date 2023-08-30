linter-autofix:
	./vendor/bin/php-cs-fixer fix -v --using-cache=no
	./vendor/bin/rector

analyze:
	./vendor/bin/phplint
	./vendor/bin/php-cs-fixer fix --dry-run --diff --using-cache=no
	./vendor/bin/rector --dry-run
	./vendor/bin/phpstan --memory-limit=-1
	./vendor/bin/psalm --no-cache $(ARGS)
	./vendor/bin/phpunit

build: docker-build
up: docker-up
down: docker-down

composer-install:
	docker compose run --rm php-fpm composer install

composer-dump:
	docker compose run --rm php-fpm composer dump-autoload

composer-update:
	docker compose run --rm php-fpm composer update

composer-outdated:
	docker compose run --rm php-fpm composer outdated

composer-dry-run:
	docker compose run --rm php-fpm composer update --dry-run

docker-up:
	docker compose up -d

docker-rebuild:
	docker compose down -v --remove-orphans
	docker compose up -d --build

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-pull:
	docker compose pull

docker-build:
	docker compose build
