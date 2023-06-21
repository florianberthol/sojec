make init: install start create-db

install:
	composer install

start:
	docker-compose up -d
	symfony server:start -d

create-db:
	bin/console doctrine:database:create
	bin/console doctrine:migration:migrate --no-interaction

stop:
	docker-compose stop
	symfony server:stop