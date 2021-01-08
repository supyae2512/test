#!/usr/bin/env bash

set -eu

echo "Set application name:"
read app_name

echo "Define local URL for this application:"
read url

echo "Define database name for this application:"
read db_name

echo "Define database user name:"
read db_user

echo "Define database user password:"
read db_password

echo "Got it! Create application configuration..."

# check if previous .env file already exists, delete if so
if [[ -f ".env" ]]; then
    rm -f ".env"
fi

echo "APP_NAME=${app_name}" >> ".env"
echo "APP_ENV=local" >> ".env"
echo "APP_KEY=" >> ".env"
echo "APP_DEBUG=true" >> ".env"
echo "APP_URL=${url}" >> ".env"
echo "\n" >> ".env"
echo "LOG_CHANNEL=stack" >> ".env"
echo "\n" >> ".env"
echo "DB_CONNECTION=mysql" >> ".env"
echo "DB_HOST=127.0.0.1" >> ".env"
echo "DB_PORT=33061" >> ".env"
echo "DB_DATABASE=${db_name}" >> ".env"
echo "DB_USERNAME=${db_user}" >> ".env"
echo "DB_PASSWORD=${db_password}" >> ".env"
echo "\n" >> ".env"
echo "BROADCAST_DRIVER=log" >> ".env"
echo "CACHE_DRIVER=file" >> ".env"
echo "QUEUE_CONNECTION=sync" >> ".env"
echo "SESSION_DRIVER=file" >> ".env"
echo "SESSION_LIFETIME=120" >> ".env"
echo "\n" >> ".env"
echo "REDIS_HOST=redis" >> ".env"
echo "REDIS_PASSWORD=null" >> ".env"
echo "REDIS_PORT=6379" >> ".env"
echo "\n" >> ".env"
echo "MAIL_DRIVER=smtp" >> ".env"
echo "MAIL_HOST=smtp.mailtrap.io" >> ".env"
echo "MAIL_PORT=2525" >> ".env"
echo "MAIL_USERNAME=null" >> ".env"
echo "MAIL_PASSWORD=null" >> ".env"
echo "MAIL_ENCRYPTION=null" >> ".env"
echo "\n" >> ".env"
echo "PUSHER_APP_ID=" >> ".env"
echo "PUSHER_APP_KEY=" >> ".env"
echo "PUSHER_APP_SECRET=" >> ".env"
echo "PUSHER_APP_CLUSTER=mt1" >> ".env"
echo "\n" >> ".env"
echo "MIX_PUSHER_APP_KEY=" >> ".env"
echo "MIX_PUSHER_APP_CLUSTER=" >> ".env"
echo "\n" >> ".env"
echo "SENTRY_LARAVEL_DSN=" >> ".env"

echo "===> Finished: creating application configuration"

# Firing up Docker containers
echo "===> Starting up Docker containers"
docker-compose up -d
echo "===> Docker containers started"

# Running Provisioning script
echo "===> Running provisioning script"
sh ./scripts/provision.sh
echo "===> Provisioning finished"

# Setting Hosts
if grep -q "${url}" /etc/hosts; then
    echo "===> ${url} already set in hosts file. Skipping..."
else
    echo "===> Add domain to hosts file"
    sudo -- sh -c "echo '127.0.0.1 ${url}' >> /etc/hosts"
fi

# Open Site in browser
open https://"${url}"

echo "===> Local installation successfully finished!"
