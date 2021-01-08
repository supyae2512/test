#!/usr/bin/env bash

set -eu

echo "===> Shutdown containers..."
docker-compose down
