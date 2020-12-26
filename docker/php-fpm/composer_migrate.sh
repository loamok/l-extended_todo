#!/bin/bash

cd /var/www/l-extended_todo

/usr/local/bin/symfony composer install -n
/usr/local/bin/symfony console doctrine:migrations:migrate --allow-no-migration --no-interaction
