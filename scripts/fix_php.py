import paramiko
import time
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

APP_DIR = "/var/www/portfolio"

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
    except Exception as e:
        print(f"Connection failed: {e}")
        return

    # Check OS
    success, output = run_command(ssh, "cat /etc/os-release", "Checking OS")
    is_debian = "ID=debian" in output or "ID_LIKE=debian" in output
    is_ubuntu = "ID=ubuntu" in output

    if "Debian" in output:
        is_debian = True
        is_ubuntu = False
    
    print(f"OS Detection: {'Debian' if is_debian else 'Ubuntu' if is_ubuntu else 'Unknown'}")

    if is_debian:
        print("Configuring for Debian...")
        cmds = [
            ("apt-get update", "Update apt"),
            ("DEBIAN_FRONTEND=noninteractive apt-get install -y lsb-release apt-transport-https ca-certificates wget", "Install prerequisites"),
            ("wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg", "Download GPG key"),
            ('echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list', "Add repo"),
            ("apt-get update", "Update apt after repo add")
        ]
        for cmd, desc in cmds:
            run_command(ssh, cmd, desc)
            
    elif is_ubuntu:
        print("Configuring for Ubuntu...")
        # Start with standard approach
        run_command(ssh, "DEBIAN_FRONTEND=noninteractive apt-get install -y software-properties-common", "Install software-properties-common")
        run_command(ssh, "add-apt-repository ppa:ondrej/php -y", "Add PPA")
        run_command(ssh, "apt-get update", "Update apt")

    # Install PHP
    extensions = "php8.3 php8.3-cli php8.3-common php8.3-curl php8.3-mbstring php8.3-xml php8.3-zip php8.3-pgsql php8.3-intl php8.3-gd php8.3-fpm"
    success, _ = run_command(ssh, f"DEBIAN_FRONTEND=noninteractive apt-get install -y {extensions}", "Installing PHP 8.3")
    
    if not success:
        print("PHP install failed again. Trying generic 'php' package just in case...")
        # fallback to default php if specific version fails? No, laravel needs 8.2+.
        # Maybe check availability
        pass

    # Re-Install Composer (it might have failed or installed broken)
    run_command(ssh, "curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php", "Download Composer")
    run_command(ssh, "php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer", "Install Composer")

    # Run deployment commands that failed
    deploy_cmds = [
        (f"cd {APP_DIR} && composer install --no-dev --optimize-autoloader", "Composer Install"),
        (f"cd {APP_DIR} && php artisan migrate --force", "Migrate"),
        (f"cd {APP_DIR} && php artisan optimize", "Optimize"),
        (f"cd {APP_DIR} && php artisan view:cache", "View Cache"),
        ("chown -R www-data:www-data /var/www/portfolio", "Fix Permissions"),
        ("systemctl restart nginx", "Restart Nginx")
    ]

    for cmd, desc in deploy_cmds:
        run_command(ssh, cmd, desc)

    # Check Nginx status
    run_command(ssh, "systemctl status nginx --no-pager", "Check Nginx Status")
    
    ssh.close()
    print("Fix script finished.")

if __name__ == "__main__":
    main()
