import paramiko
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

APP_DIR = "/var/www/portfolio"

NGINX_CONF = """server {
    listen 80;
    server_name great-x-attach.xyz portfolio.great-x-attach.xyz;
    root /var/www/portfolio/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \\.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\\.(?!well-known).* {
        deny all;
    }
}
"""

def run_command(ssh, command, description):
    print(f"[{description}] Running: {command}")
    stdin, stdout, stderr = ssh.exec_command(command)
    exit_status = stdout.channel.recv_exit_status()
    out = stdout.read().decode().strip()
    err = stderr.read().decode().strip()
    
    if exit_status != 0:
        print(f"  ERROR: {err}")
        return False, out + "\n" + err
    if out:
        print(f"  OK: {out[:200]}")
    else:
        print(f"  OK")
    return True, out

def main():
    print(f"Connecting to {HOST}...")
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    
    try:
        ssh.connect(HOST, username=USERNAME, password=PASSWORD)
        print("Connected.\n")
    except Exception as e:
        print(f"Connection failed: {e}")
        return

    # 1. Update Nginx config to serve both domains
    print("=" * 50)
    print("Step 1: Updating Nginx configuration")
    print("=" * 50)
    sftp = ssh.open_sftp()
    with sftp.file("/etc/nginx/sites-available/portfolio", "w") as f:
        f.write(NGINX_CONF)
    sftp.close()
    print("  Nginx config written with server_name: great-x-attach.xyz portfolio.great-x-attach.xyz")

    # Ensure symlink exists
    run_command(ssh, "ln -sf /etc/nginx/sites-available/portfolio /etc/nginx/sites-enabled/", "Ensuring site is enabled")

    # Test nginx config
    success, _ = run_command(ssh, "nginx -t", "Testing Nginx config")
    if not success:
        print("Nginx config test FAILED! Aborting.")
        ssh.close()
        return

    # 2. Update Laravel .env
    print("\n" + "=" * 50)
    print("Step 2: Updating Laravel .env (APP_URL & ASSET_URL)")
    print("=" * 50)
    run_command(ssh, f"sed -i 's|APP_URL=.*|APP_URL=https://great-x-attach.xyz|g' {APP_DIR}/.env", "Updating APP_URL")
    run_command(ssh, f"grep -q 'ASSET_URL' {APP_DIR}/.env && sed -i 's|ASSET_URL=.*|ASSET_URL=https://great-x-attach.xyz|g' {APP_DIR}/.env || echo 'ASSET_URL=https://great-x-attach.xyz' >> {APP_DIR}/.env", "Updating ASSET_URL")

    # 3. Clear Laravel caches
    print("\n" + "=" * 50)
    print("Step 3: Clearing Laravel caches")
    print("=" * 50)
    run_command(ssh, f"cd {APP_DIR} && php artisan config:clear", "Clearing config cache")
    run_command(ssh, f"cd {APP_DIR} && php artisan route:clear", "Clearing route cache")
    run_command(ssh, f"cd {APP_DIR} && php artisan view:clear", "Clearing view cache")
    run_command(ssh, f"cd {APP_DIR} && php artisan optimize", "Re-optimizing")

    # 4. Restart services
    print("\n" + "=" * 50)
    print("Step 4: Restarting services")
    print("=" * 50)
    run_command(ssh, "systemctl restart php8.3-fpm", "Restarting PHP-FPM")
    run_command(ssh, "systemctl restart nginx", "Restarting Nginx")

    # 5. Verify
    print("\n" + "=" * 50)
    print("Step 5: Verification")
    print("=" * 50)
    run_command(ssh, "cat /etc/nginx/sites-available/portfolio | grep server_name", "Checking server_name")
    run_command(ssh, f"grep APP_URL {APP_DIR}/.env", "Checking APP_URL")
    run_command(ssh, f"grep ASSET_URL {APP_DIR}/.env", "Checking ASSET_URL")
    run_command(ssh, "curl -s -o /dev/null -w '%{http_code}' http://localhost", "Checking HTTP response")

    print("\n" + "=" * 50)
    print("DONE!")
    print("=" * 50)
    print("""
Next steps:
  1. Go to Cloudflare Zero Trust Dashboard
  2. Navigate to: Networks -> Tunnels -> your tunnel -> Public Hostname
  3. Add a new public hostname:
     - Subdomain: (leave empty for root domain)
     - Domain: great-x-attach.xyz
     - Service Type: HTTP
     - URL: localhost:80
  4. Make sure DNS for great-x-attach.xyz points to Cloudflare (proxied)
  
After that, https://great-x-attach.xyz should serve the same portfolio!
""")

    ssh.close()

if __name__ == "__main__":
    main()
