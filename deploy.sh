#!/bin/bash
# =============================================================================
# deploy.sh — Portfolio-LV Production Deploy Script
# Usage: bash deploy.sh
# Run from: /var/www/portfolio-lv (or wherever the app lives on server)
# =============================================================================

set -e  # Exit on any error

APP_DIR="/var/www/portfolio-lv"
PHP="php8.2"
COMPOSER="composer"

echo ""
echo "╔══════════════════════════════════════════════╗"
echo "║      Portfolio-LV — Production Deploy        ║"
echo "╚══════════════════════════════════════════════╝"
echo ""

cd "$APP_DIR"

# ── 1. Pull latest code ──────────────────────────────────────────────────────
echo "▶ [1/8] Pulling latest code from GitHub..."
git pull origin main

# ── 2. Install PHP dependencies ──────────────────────────────────────────────
echo "▶ [2/8] Installing Composer dependencies (no-dev)..."
$COMPOSER install --optimize-autoloader --no-dev --no-interaction

# ── 3. Install & build Node assets ───────────────────────────────────────────
echo "▶ [3/8] Building frontend assets..."
npm ci --silent
npm run build

# ── 4. Run migrations ────────────────────────────────────────────────────────
echo "▶ [4/8] Running database migrations..."
$PHP artisan migrate --force

# ── 5. Cache everything ───────────────────────────────────────────────────────
echo "▶ [5/8] Caching config, routes, views..."
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache
$PHP artisan event:cache

# ── 6. Storage link ───────────────────────────────────────────────────────────
echo "▶ [6/8] Linking storage..."
$PHP artisan storage:link --force 2>/dev/null || true

# ── 7. Clear stale caches ─────────────────────────────────────────────────────
echo "▶ [7/8] Clearing stale application cache..."
$PHP artisan cache:clear

# ── 8. Fix permissions ────────────────────────────────────────────────────────
echo "▶ [8/8] Fixing permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo ""
echo "✅ Deploy complete!"
echo ""
$PHP artisan about --only=Environment
