#!/usr/bin/env bash

set -eu

echo "===> Stopping containers..."
docker stop $(docker ps -aq)

echo "===> Removing containers..."
docker rm $(docker ps -a -q)

echo "===> Removing images..."
docker rmi $(docker images -q)
