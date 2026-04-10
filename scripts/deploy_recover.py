import paramiko
import time
import os
import sys
from config import HOST, USERNAME, PASSWORD as PASS, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

# SSH Connection Details

def run_command(ssh, command, description):
    print(f"[{description}] Running...")
    try:
        stdin, stdout, stderr = ssh.exec_command(command, timeout=15)
        exit_status = stdout.channel.recv_exit_status()
        out = stdout.read().decode().strip()
        err = stderr.read().decode().strip()
        
        if exit_status != 0:
            print(f"Error: {err}")
        else:
            print("Success")
        print(out)
    except Exception as e:
        print(f"Command failed execution: {e}")

try:
    print(f"Connecting to {HOST}...")
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    ssh.connect(HOST, username=USER, password=PASS, timeout=10)
    print("Connected.")

    # 1. Retry PHP Config (idempotent)
    php_ini_cmd = """
    for VER in 8.2 8.3 8.1; do
        INI_FILE="/etc/php/$VER/fpm/php.ini"
        if [ -f "$INI_FILE" ]; then
            echo "Found PHP $VER ini at $INI_FILE"
            # distinct sed commands to avoid complexity
            sed -i 's/upload_max_filesize = .*/upload_max_filesize = 10M/' "$INI_FILE"
            sed -i 's/post_max_size = .*/post_max_size = 10M/' "$INI_FILE"
            systemctl restart php$VER-fpm
        fi
    done
    """
    run_command(ssh, php_ini_cmd, "Configuring PHP upload limits (Retry)")

    # 2. Clear Caches
    run_command(ssh, "cd /var/www/portfolio && php artisan config:clear && php artisan view:clear && php artisan route:clear", "Clearing Laravel Caches")
    
    # 3. Ensure Storage Link Exists
    run_command(ssh, "cd /var/www/portfolio && php artisan storage:link", "Ensuring Storage Link")
    
    # 4. Set Permissions (Crucial for uploads)
    run_command(ssh, "chown -R www-data:www-data /var/www/portfolio/storage /var/www/portfolio/bootstrap/cache", "Fixing Permissions")
    
    # 5. Verify Nginx Config
    run_command(ssh, "grep client_max_body_size /etc/nginx/sites-available/portfolio", "Verifying Nginx Config")

    print("\nRecovery Deployment Complete!")

except Exception as e:
    print(f"An error occurred: {e}")
finally:
    if 'ssh' in locals():
        ssh.close()
