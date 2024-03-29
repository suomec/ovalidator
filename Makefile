docker-start:
	docker-compose up -d

docker-session:
	docker exec -it suomec-ovalidator-php81 /bin/bash

docker-rebuild:
	docker-compose up -d --build suomec-ovalidator-php81

check:
	./var/vendor/bin/php-cs-fixer --dry-run --using-cache=no --diff fix ./src
	./var/vendor/bin/php-cs-fixer --dry-run --using-cache=no --diff fix ./tests
	./var/vendor/bin/php-cs-fixer --dry-run --using-cache=no --diff fix ./examples
	./var/vendor/bin/phpstan --autoload-file=./var/vendor/autoload.php analyze --level max src tests examples

test:
	./var/vendor/bin/phpunit --colors=always ./tests

filter-test:
	./var/vendor/bin/phpunit --colors=always ./tests --filter "$(FILTER)"
