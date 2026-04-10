import paramiko
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

APP_DIR = "/var/www/portfolio"

def main():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    try:
        ssh.connect(HOST, username=USERNAME, password=PASSWORD)
        
        print("Updating apt...")
        ssh.exec_command("apt-get update")
        
        print("Installing extensions...")
        extensions = "php8.3-zip php8.3-xml php8.3-mbstring php8.3-curl php8.3-pgsql php8.3-intl php8.3-gd"
        stdin, stdout, stderr = ssh.exec_command(f"DEBIAN_FRONTEND=noninteractive apt-get install -y {extensions}")
        print(stdout.read().decode())
        print(stderr.read().decode())
        
        print("Running composer install again...")
        stdin, stdout, stderr = ssh.exec_command(f"cd {APP_DIR} && composer install --no-dev --optimize-autoloader -v")
        print(stdout.read().decode())
        print(stderr.read().decode())
        
        print("Running Permissions Fix...")
        ssh.exec_command(f"chown -R www-data:www-data {APP_DIR}")
        
    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
