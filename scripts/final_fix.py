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
        
        print("--- Fixing Permissions on Public ---")
        ssh.exec_command(f"chown -R www-data:www-data {APP_DIR}/public")
        ssh.exec_command(f"chmod -R 755 {APP_DIR}/public")
        
        print("--- Clearing Caches ---")
        ssh.exec_command(f"cd {APP_DIR} && php artisan optimize:clear")
        ssh.exec_command(f"cd {APP_DIR} && php artisan view:cache")
        ssh.exec_command(f"cd {APP_DIR} && php artisan config:cache")
        
        print("Done.")

    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
