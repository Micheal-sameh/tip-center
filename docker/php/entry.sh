#!/bin/bash
# ─────────────────────────────────────────────────────────────────────────────
#  Tip-Center · Docker Entrypoint
#  Runs on container start before launching the main process (supervisord).
# ─────────────────────────────────────────────────────────────────────────────
set -e

APP_DIR="/var/www/html"
cd "${APP_DIR}"

# ── 1. Wait for MariaDB ───────────────────────────────────────────────────────
echo "[entry] Waiting for database at ${DB_HOST:-db}:${DB_PORT:-3306}..."
until mysqladmin ping \
    -h"${DB_HOST:-db}" \
    -P"${DB_PORT:-3306}" \
    -u"${DB_USERNAME}" \
    -p"${DB_PASSWORD}" \
    --silent 2>/dev/null; do
    echo "[entry]   Not ready yet, retrying in 3s..."
    sleep 3
done
echo "[entry] Database is ready."

# ── 2. Ensure .env exists ─────────────────────────────────────────────────────
if [ ! -f "${APP_DIR}/.env" ]; then
    echo "[entry] .env not found – copying .env.example"
    cp "${APP_DIR}/.env.example" "${APP_DIR}/.env"
fi

# ── 3. Generate app key if missing ───────────────────────────────────────────
if ! grep -q '^APP_KEY=base64:' "${APP_DIR}/.env"; then
    echo "[entry] Generating APP_KEY..."
    php artisan key:generate --force
fi

# ── 4. Install Composer dependencies if vendor/ is absent ────────────────────
if [ ! -d "${APP_DIR}/vendor" ]; then
    echo "[entry] vendor/ not found – running composer install..."
    composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
fi

# ── 5. Fix storage & cache permissions ───────────────────────────────────────
echo "[entry] Setting permissions..."
chown -R www-data:www-data \
    "${APP_DIR}/storage" \
    "${APP_DIR}/bootstrap/cache"
chmod -R 775 \
    "${APP_DIR}/storage" \
    "${APP_DIR}/bootstrap/cache"

# ── 6. Run migrations (can be disabled with RUN_MIGRATIONS=false) ─────────────
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "[entry] Running migrations..."
    php artisan migrate --force
fi

# ── 7. Create public/storage symlink ─────────────────────────────────────────
if [ ! -L "${APP_DIR}/public/storage" ]; then
    echo "[entry] Creating storage symlink..."
    php artisan storage:link
fi

# ── 8. Optimise (cache config, routes, views) ────────────────────────────────
if [ "${APP_ENV:-production}" = "production" ]; then
    echo "[entry] Caching config, routes and views..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
else
    # Clear any stale caches in local/dev
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
fi

echo "[entry] Bootstrap complete. Starting: $*"
exec "$@"
