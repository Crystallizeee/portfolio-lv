#!/bin/bash
# =============================================================================
# PORTFOLIO-LV — Full Server Setup & Deploy Script (ROOT VERSION)
# Jalankan ini di server: bash setup_production.sh
# Server: devuser@192.168.1.250 (TurnKey Debian 12)
# =============================================================================

set -e
RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; CYAN='\033[0;36m'; NC='\033[0m'
log()  { echo -e "${GREEN}▶ $1${NC}"; }
warn() { echo -e "${YELLOW}⚠ $1${NC}"; }
err()  { echo -e "${RED}✗ $1${NC}"; exit 1; }

APP_DIR="/var/www/portfolio-lv"
REPO="https://github.com/Crystallizeee/portfolio-lv.git"
WEBUSER="www-data"

echo -e "${CYAN}"
echo "╔══════════════════════════════════════════════════════╗"
echo "║   Portfolio-LV — Production Setup & Deploy          ║"
echo "║   Server: $(hostname) | $(date)     ║"
echo "╚══════════════════════════════════════════════════════╝"
echo -e "${NC}"

# =============================================================================
# 1. INSTALL DEPENDENCIES (kalau belum ada)
# =============================================================================
log "[1/10] Checking & installing system dependencies..."

# PHP
if ! command -v php &>/dev/null; then
    log "Installing PHP 8.2..."
    apt-get update -qq
    apt-get install -y php8.2 php8.2-fpm php8.2-pgsql php8.2-mbstring \
        php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-intl php8.2-redis \
        php8.2-bcmath php8.2-tokenizer
else
    echo "  PHP: $(php -v | head -1)"
fi

# Composer
if ! command -v composer &>/dev/null; then
    log "Installing Composer..."
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
else
    echo "  Composer: $(composer --version 2>/dev/null | head -1)"
fi

# Node.js
if ! command -v node &>/dev/null; then
    log "Installing Node.js 20..."
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    apt-get install -y nodejs
else
    echo "  Node: $(node -v) | npm: $(npm -v)"
fi

# Git
if ! command -v git &>/dev/null; then
    apt-get install -y git
fi

# Nginx
if ! command -v nginx &>/dev/null; then
    log "Installing Nginx..."
    apt-get install -y nginx
fi

# =============================================================================
# 2. SETUP APP DIRECTORY
# =============================================================================
log "[2/10] Setting up app directory..."

if [ -d "$APP_DIR/.git" ]; then
    log "Repo already exists — pulling latest..."
    cd "$APP_DIR"
    git fetch origin
    git reset --hard origin/main
    git pull origin main
else
    log "Cloning repository..."
    mkdir -p /var/www
    git clone "$REPO" "$APP_DIR"
    cd "$APP_DIR"
fi

# Ensure devuser owns the directory if we're deploying as root but want to keep devuser
chown -R devuser:devuser "$APP_DIR"
cd "$APP_DIR"

# =============================================================================
# 3. CREATE .env PRODUCTION
# =============================================================================
log "[3/10] Creating production .env..."

cat > .env << 'ENVEOF'
APP_NAME="Cyber Portfolio"
APP_ENV=production
APP_KEY=base64:Hh6WKfJvlGdDwSI+3Po+lkCVr4HzxSzVu7MpUMpURkQ=
APP_DEBUG=false
APP_URL=http://192.168.1.250

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=warning
LOG_DAYS=14

DB_CONNECTION=pgsql
DB_HOST=192.168.1.221
DB_PORT=5432
DB_DATABASE=portfolio
DB_USERNAME=postgres
DB_PASSWORD=CHANGE_ME_DB_PASSWORD

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

PROXMOX_HOST=192.168.1.200
PROXMOX_NODE=home
PROXMOX_TOKEN_ID=CHANGE_ME_PROXMOX_TOKEN_ID
PROXMOX_TOKEN_SECRET=CHANGE_ME_PROXMOX_SECRET

GEMINI_API_KEY=CHANGE_ME_GEMINI_KEY

# Ollama Cloud API
OLLAMA_API_KEY=CHANGE_ME_OLLAMA_KEY
OLLAMA_API_URL=https://ollama.com/v1
OLLAMA_MODEL=gemma3:27b
OLLAMA_MODEL_SEO=gpt-oss:120b

CACHE_STORE=database

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="Cyber Portfolio"

VITE_APP_NAME="Cyber Portfolio"

CV_API_TOKEN=CHANGE_ME_CV_TOKEN

GITHUB_USERNAME=Crystallizeee
GITHUB_TOKEN=CHANGE_ME_GITHUB_TOKEN

# Security
FORCE_HTTPS=false
TRUSTED_PROXIES=

# AI Security
AI_CIRCUIT_BREAKER_MAX_FAILS=5
AI_CIRCUIT_BREAKER_BLOCK_SECONDS=300
ENVEOF

echo "  .env created."

# =============================================================================
# 4. COMPOSER INSTALL
# =============================================================================
log "[4/10] Installing PHP dependencies..."
# Run as devuser to avoid permission issues later
su devuser -s /bin/bash -c "composer install --optimize-autoloader --no-dev --no-interaction"

# =============================================================================
# 5. NPM BUILD
# =============================================================================
log "[5/10] Building frontend assets..."
su devuser -s /bin/bash -c "npm ci --silent && npm run build"

# =============================================================================
# 6. DATABASE MIGRATIONS
# =============================================================================
log "[6/10] Running database migrations..."
su devuser -s /bin/bash -c "php artisan migrate --force"

# =============================================================================
# 7. CACHE OPTIMIZATION
# =============================================================================
log "[7/10] Caching config, routes, views..."
su devuser -s /bin/bash -c "php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan event:cache"

# =============================================================================
# 8. STORAGE & PERMISSIONS
# =============================================================================
log "[8/10] Setting up storage..."
su devuser -s /bin/bash -c "php artisan storage:link --force 2>/dev/null || true"
chown -R $WEBUSER:$WEBUSER storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# =============================================================================
# 9. NGINX CONFIGURATION
# =============================================================================
log "[9/10] Configuring Nginx..."

tee /etc/nginx/sites-available/portfolio-lv > /dev/null << 'NGINXEOF'
server {
    listen 80;
    server_name 192.168.1.250 _;

    root /var/www/portfolio-lv/public;
    index index.php;

    client_max_body_size 50M;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Gzip
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml image/svg+xml;
    gzip_min_length 1000;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 60;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff2|svg)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Block access to sensitive files
    location ~ ^/(\.env|composer\.(json|lock)|package\.json) {
        deny all;
        return 404;
    }
}
NGINXEOF

# Enable site
ln -sf /etc/nginx/sites-available/portfolio-lv /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default 2>/dev/null || true

# Test & reload nginx
nginx -t && systemctl reload nginx && systemctl enable nginx

# Start PHP-FPM
systemctl enable php8.2-fpm
systemctl start php8.2-fpm || systemctl restart php8.2-fpm

# =============================================================================
# 10. FINAL CHECK
# =============================================================================
log "[10/10] Final verification..."
su devuser -s /bin/bash -c "php artisan about --only=Environment 2>/dev/null || php artisan --version"

echo ""
echo -e "${GREEN}╔══════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║              ✅ DEPLOY COMPLETE!                     ║${NC}"
echo -e "${GREEN}╚══════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e "  🌐 App URL : ${CYAN}http://192.168.1.250${NC}"
echo -e "  📁 App Dir : ${CYAN}$APP_DIR${NC}"
echo -e "  📋 Logs    : ${CYAN}$APP_DIR/storage/logs/laravel.log${NC}"
echo ""
echo -e "  ${YELLOW}Kalau domain sudah aktif, update APP_URL & FORCE_HTTPS=true di .env${NC}"
echo ""
