#!/usr/bin/env bash
set -euo pipefail

APP_DIR="/home/guy-wilfried/www/portfolio"
REPO_URL="https://github.com/most-wilfried/portfolio-back.git"

echo "== Portfolio Laravel deploy on Alwaysdata =="

read -r -p "Frontend Vercel URL (example: https://portfolio.vercel.app): " FRONTEND_URL
read -r -s -p "Aiven DB password: " DB_PASSWORD
echo

mkdir -p /home/guy-wilfried/www

if [ -d "$APP_DIR/.git" ]; then
  echo "== Updating existing repository =="
  cd "$APP_DIR"
  git fetch origin main
  git reset --hard origin/main
else
  echo "== Cloning repository =="
  rm -rf "$APP_DIR"
  git clone "$REPO_URL" "$APP_DIR"
  cd "$APP_DIR"
fi

echo "== Writing production .env =="
cp .env.alwaysdata.example .env
python3 - <<PY
from pathlib import Path

env = Path(".env")
text = env.read_text()
replacements = {
    "APP_ENV=production": "APP_ENV=production",
    "APP_DEBUG=false": "APP_DEBUG=false",
    "APP_URL=https://guy-wilfried.alwaysdata.net": "APP_URL=https://guy-wilfried.alwaysdata.net",
    "FRONTEND_URL=PASTE_YOUR_VERCEL_URL_HERE": "FRONTEND_URL=${FRONTEND_URL}",
    "DB_PASSWORD=PASTE_AIVEN_PASSWORD_HERE": "DB_PASSWORD=${DB_PASSWORD}",
    "FILESYSTEM_DISK=public": "FILESYSTEM_DISK=public",
}
for old, new in replacements.items():
    text = text.replace(old, new)
if "FILES_PUBLIC_URL=" not in text:
    text += "\nFILES_PUBLIC_URL=\n"
env.write_text(text)
PY

echo "== Installing PHP dependencies =="
composer install --no-dev --optimize-autoloader

echo "== Preparing writable directories =="
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "== Clearing caches =="
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "== Linking storage =="
php artisan storage:link || true

echo "== Running migrations =="
php artisan migrate --force

echo "== Checking database =="
php artisan tinker --execute="dump(DB::select('select database() as db, version() as version'));"

echo "== Checking routes =="
php artisan route:list --path=api/storage-audit
php artisan route:list --path=storage

echo "== Done =="
echo "Now set the Alwaysdata site root to:"
echo "$APP_DIR/public"
echo
echo "Then test:"
echo "https://guy-wilfried.alwaysdata.net/api/health"
echo "https://guy-wilfried.alwaysdata.net/api/storage-audit"
