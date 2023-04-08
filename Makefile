start.dev:
	docker compose up -d

start.prod:
	docker compose -f docker-compose.prod.yml up -d

stop:
	docker compose stop

restart.dev:
	make stop
	make start.dev

reboot.dev:
	make down
	make start.dev

down:
	docker compose down

composer.install:
	docker compose run --rm php composer install

enter.php:
	docker compose exec -it php bash

run.tests:
	docker compose run --rm php ./vendor/bin/phpunit --bootstrap tests/bootstrap.php --configuration ./phpunit.xml.dist

run.test:
ifdef class
	docker compose run --rm php ./vendor/bin/phpunit --bootstrap tests/bootstrap.php $(class) --configuration ./phpunit.xml.dist
else
	@echo 'please add classpath in format `make run.test class='classpath'`'
endif

phpstan:
	docker compose run --rm php composer run-phpstan

phpcs:
	docker compose run --rm php ./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --dry-run -vv --diff