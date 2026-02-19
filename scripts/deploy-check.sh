#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

echo "==> composer install --optimize-autoloader --no-dev"
composer install --optimize-autoloader --no-dev

echo "==> php artisan migrate --force"
php artisan migrate --force

echo "==> php artisan config:cache"
php artisan config:cache

echo "==> php artisan route:cache"
php artisan route:cache

echo "==> php artisan view:cache"
php artisan view:cache

echo "Deploy checks passed."
