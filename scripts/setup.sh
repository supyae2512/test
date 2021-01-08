#!/usr/bin/env bash

# Include YAML parsing script
source ./scripts/yaml.sh

# read docker-compose.yml
create_variables ./docker-compose.yml

URL=$services_nginx_environment_SERVER_NAME

# Firing up Docker containers
echo "===> Starting up Docker containers"
docker-compose up -d
echo "===> Docker containers started"

# Running Provisioning script
echo "===> Running provisioning script"
sh ./scripts/provision.sh
echo "===> Provisioning finished"

# Setting Hosts
echo "===> Add domain to hosts file"
sudo -- sh -c "echo '127.0.0.1 ${URL}' >> /etc/hosts"

# Open Site in browser
open https://"${URL}"

echo "===> Local setup successfully finished!"
