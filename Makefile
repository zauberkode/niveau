databases:
	docker compose exec php82-service bin/console doctrine:database:create
	docker compose exec php82-service bin/console doctrine:schema:create
	docker compose exec php82-service bin/console --env=test doctrine:database:create
	docker compose exec php82-service bin/console --env=test doctrine:schema:create
tests:
	docker compose exec php82-service bin/phpunit
fix:
	docker compose exec php82-service vendor/bin/php-cs-fixer fix

