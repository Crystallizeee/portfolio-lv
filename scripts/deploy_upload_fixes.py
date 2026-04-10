import paramiko
import os
import time
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

# SSH Connection Details
PASS = PASSWORD

def run_command(ssh, command, description):
    print(f"[{description}] Running...")
    stdin, stdout, stderr = ssh.exec_command(command)
    exit_status = stdout.channel.recv_exit_status()
    out = stdout.read().decode().strip()
    err = stderr.read().decode().strip()
    
    if exit_status != 0:
        print(f"Error: {err}")
    else:
        print("Success")
    return out

def upload_file(sftp, local_path, remote_path):
    print(f"Uploading {local_path} -> {remote_path}")
    try:
        sftp.put(local_path, remote_path)
        print("Upload successful")
    except Exception as e:
        print(f"Upload failed: {e}")

try:
    print(f"Connecting to {HOST}...")
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    ssh.connect(HOST, username=USER, password=PASS)
    sftp = ssh.open_sftp()
    print("Connected.")

    # 1. Upload modified files
    # Only upload files that were modified.
    files_to_upload = [
        ('bootstrap/app.php', '/var/www/portfolio/bootstrap/app.php'),
        ('config/livewire.php', '/var/www/portfolio/config/livewire.php'), # New file
        ('app/Livewire/Admin/ProfileSettings.php', '/var/www/portfolio/app/Livewire/Admin/ProfileSettings.php'),
        ('resources/views/livewire/admin/profile-settings.blade.php', '/var/www/portfolio/resources/views/livewire/admin/profile-settings.blade.php'),
    ]

    for local, remote in files_to_upload:
        local_path = os.path.join(r"d:\src_code\Py\Portfolio\portfolio-lv", local)
        # Ensure remote directory exists for new files
        remote_dir = os.path.dirname(remote)
        run_command(ssh, f"mkdir -p {remote_dir}", f"Ensure dir exists: {remote_dir}")
        upload_file(sftp, local_path, remote)

    # 2. Configure Nginx (Client Max Body Size)
    # Be careful not to append multiple times. Check if it exists first.
    nginx_conf_cmd = """
    CONF="/etc/nginx/sites-available/portfolio"
    if grep -q "client_max_body_size" "$CONF"; then
        sed -i 's/client_max_body_size.*/client_max_body_size 10M;/' "$CONF"
    else
        sed -i '/server_name/a \    client_max_body_size 10M;' "$CONF"
    fi
    nginx -t && systemctl reload nginx
    """
    run_command(ssh, nginx_conf_cmd, "Configuring Nginx body limit")

    # 3. Configure PHP (Upload Limits)
    # Try different versions
    php_ini_cmd = """
    for VER in 8.2 8.3 8.1; do
        INI_FILE="/etc/php/$VER/fpm/php.ini"
        if [ -f "$INI_FILE" ]; then
            echo "Found PHP $VER ini at $INI_FILE"
            sed -i 's/upload_max_filesize = .*/upload_max_filesize = 10M/' "$INI_FILE"
            sed -i 's/post_max_size = .*/post_max_size = 10M/' "$INI_FILE"
            systemctl restart php$VER-fpm
        fi
    done
    """
    run_command(ssh, php_ini_cmd, "Configuring PHP upload limits")

    # 4. Clear Caches
    run_command(ssh, "cd /var/www/portfolio && php artisan config:clear && php artisan view:clear && php artisan route:clear", "Clearing Laravel Caches")
    
    # 5. Ensure Storage Link Exists
    run_command(ssh, "cd /var/www/portfolio && php artisan storage:link", "Ensuring Storage Link")
    
    # 6. Set Permissions
    run_command(ssh, "chown -R www-data:www-data /var/www/portfolio/storage /var/www/portfolio/bootstrap/cache", "Fixing Permissions")

    print("\nDeployment Complete!")

except Exception as e:
    print(f"An error occurred: {e}")
finally:
    if 'ssh' in locals():
        ssh.close()
