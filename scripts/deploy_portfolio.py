import paramiko
import time
import sys
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR, REPO_URL, PROXMOX_TOKEN_ID, PROXMOX_TOKEN_SECRET, GEMINI_API_KEY
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

# Configuration
APP_DIR = "/var/www/portfolio"

ENV_CONTENT = f"""APP_NAME="Cyber Portfolio"
APP_ENV=local
APP_KEY=base64:Hh6WKfJvlGdDwSI+3Po+lkCVr4HzxSzVu7MpUMpURkQ=
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

# PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=192.168.1.221
DB_PORT=5432
DB_DATABASE=portfolio
DB_USERNAME=postgres
DB_PASSWORD=admin123

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
PROXMOX_TOKEN_ID={PROXMOX_TOKEN_ID}
PROXMOX_TOKEN_SECRET={PROXMOX_TOKEN_SECRET}

GEMINI_API_KEY={GEMINI_API_KEY}

CACHE_STORE=database
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${{APP_NAME}}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${{APP_NAME}}"
"""

def run_command(ssh, command, description):
    print(f"[{description}] Running: {command}")
    stdin, stdout, stderr = ssh.exec_command(command)
    exit_status = stdout.channel.recv_exit_status()
    out = stdout.read().decode().strip()
    err = stderr.read().decode().strip()
    
    if exit_status != 0:
        print(f"Error executing {command}: {err}")
        return False, out + "\n" + err
    return True, out

def main():
    print(f"Connecting to {HOST}...")
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    
    try:
        ssh.connect(HOST, username=USERNAME, password=PASSWORD)
        print("Connected.")
    except Exception as e:
        print(f"Connection failed: {e}")
        return

    # 1. Prepare Environment
    print("Preparing server environment...")
    
    commands = [
        ("apt-get update", "Updating package lists"),
        ("DEBIAN_FRONTEND=noninteractive apt-get install -y git zip unzip curl software-properties-common", "Installing base tools"),
    ]
    
    # Check if PHP 8.3 is available or needs PPA
    # For simplicity, we add the PPA
    commands.append(("add-apt-repository ppa:ondrej/php -y", "Adding PHP PPA"))
    commands.append(("apt-get update", "Updating package lists after PPA"))
    
    extensions = "php8.3 php8.3-cli php8.3-common php8.3-curl php8.3-mbstring php8.3-xml php8.3-zip php8.3-pgsql php8.3-intl php8.3-gd php8.3-fpm"
    commands.append((f"DEBIAN_FRONTEND=noninteractive apt-get install -y {extensions}", "Installing PHP 8.3 and extensions"))
    
    # Install Composer
    commands.append(("curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php", "Downloading Composer installer"))
    commands.append(("php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer", "Installing Composer"))
    
    # Install Node.js 20
    commands.append(("curl -fsSL https://deb.nodesource.com/setup_20.x | bash -", "Setting up Node.js repo"))
    commands.append(("DEBIAN_FRONTEND=noninteractive apt-get install -y nodejs", "Installing Node.js"))
    
    # Install Nginx
    commands.append(("DEBIAN_FRONTEND=noninteractive apt-get install -y nginx", "Installing Nginx"))

    for cmd, desc in commands:
        success, output = run_command(ssh, cmd, desc)
        if not success:
            print(f"Failed step: {desc}")
            # Continue? Maybe critical failure but lets run subsequent commands might fail too. 
            # For now, print error and mostly continue unless it's critical. 
            # If php install fails, everything else will.
            # We'll stick to a simple script.

    # 2. Deploy Code
    print("Deploying application...")
    
    # Check if dir exists
    success, output = run_command(ssh, f"[ -d {APP_DIR} ] && echo 'exists'", "Checking app directory")
    if "exists" in output:
        # Pull
        run_command(ssh, f"cd {APP_DIR} && git pull", "Pulling latest changes")
    else:
        # Clone
        run_command(ssh, f"git clone {REPO_URL} {APP_DIR}", "Cloning repository")

    # Write .env
    print("Writing .env file...")
    # Escape quotes if necessary, but writing via python paramiko sftp is safer
    sftp = ssh.open_sftp()
    with sftp.file(f"{APP_DIR}/.env", "w") as f:
        f.write(ENV_CONTENT)
    sftp.close()
    
    # Fix permissions
    run_command(ssh, f"chown -R www-data:www-data {APP_DIR}", "Setting permissions")
    run_command(ssh, f"chmod -R 775 {APP_DIR}/storage {APP_DIR}/bootstrap/cache", "Setting specific permissions")

    # Install Dependencies
    deploy_cmds = [
        (f"cd {APP_DIR} && composer install --no-dev --optimize-autoloader", "Installing PHP dependencies"),
        (f"cd {APP_DIR} && npm ci", "Installing Node dependencies"),
        (f"cd {APP_DIR} && npm run build", "Building assets"),
        (f"cd {APP_DIR} && php artisan migrate --force", "Running migrations"),
        (f"cd {APP_DIR} && php artisan optimize", "Optimizing application"),
        (f"cd {APP_DIR} && php artisan view:cache", "Caching views"),
    ]

    for cmd, desc in deploy_cmds:
        success, output = run_command(ssh, cmd, desc)
        if not success:
            print(f"Failed: {desc} -> {output}")

    # 3. Configure Nginx
    nginx_conf = f"""server {{
    listen 80;
    server_name portfolio.great-x-attach.xyz;
    root {APP_DIR}/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {{
        try_files $uri $uri/ /index.php?$query_string;
    }}

    location = /favicon.ico {{ access_log off; log_not_found off; }}
    location = /robots.txt  {{ access_log off; log_not_found off; }}

    error_page 404 /index.php;

    location ~ \\.php$ {{
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }}

    location ~ /\\.(?!well-known).* {{
        deny all;
    }}
}}
"""
    print("Configuring Nginx...")
    sftp = ssh.open_sftp()
    with sftp.file("/etc/nginx/sites-available/portfolio", "w") as f:
        f.write(nginx_conf)
    sftp.close()
    
    run_command(ssh, "ln -sf /etc/nginx/sites-available/portfolio /etc/nginx/sites-enabled/", "Enabling site")
    run_command(ssh, "rm -f /etc/nginx/sites-enabled/default", "Removing default site")
    run_command(ssh, "systemctl restart nginx", "Restarting Nginx")

    # 4. Cloudflared (Optional/Manual check)
    # Checking if cloudflared is installed
    success, _ = run_command(ssh, "which cloudflared", "Checking cloudflared")
    if not success:
        print("Cloudflared not found. Installing...")
        run_command(ssh, "curl -L --output cloudflared.deb https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64.deb", "Downloading cloudflared")
        run_command(ssh, "dpkg -i cloudflared.deb", "Installing cloudflared")

    # Configure tunnel if needed
    # The user provided a tunnel ID. Usually you need to run `cloudflared service install <token>`
    # I don't have the token (only UUID).
    print("Cloudflare Setup: Ensure the tunnel is running. If not, run 'cloudflared service install <token>' on the server.")

    ssh.close()
    print("Deployment script finished.")

if __name__ == "__main__":
    main()
