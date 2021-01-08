#!/usr/bin/env bash

set -eu

APP_DIR="/var/www/"

cd $APP_DIR && composer install -o && composer dump-autoload
echo "===> Finished installation of dependencies."
