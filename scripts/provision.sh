#!/usr/bin/env bash

# Initial provisioning script for local development.
docker-compose exec php sh -c 'sh scripts/composer.sh'
echo "===> Completed: Composer"

# Initial provisioning script for local development.
docker-compose exec php sh -c 'sh scripts/artisan.sh'
echo "===> Completed: Artisan"
