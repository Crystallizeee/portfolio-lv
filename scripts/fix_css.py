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
        
        print("--- Updating .env APP_URL ---")
        # Use sed to replace APP_URL
        ssh.exec_command(f"sed -i 's|APP_URL=http://localhost|APP_URL=https://portfolio.great-x-attach.xyz|g' {APP_DIR}/.env")
        
        print("--- Adding ASSET_URL ---")
        # Check if ASSET_URL exists, if not append it, or replace it if it does
        # For simplicity, we'll just append it if not strictly managing it, but let's try just APP_URL first.
        # Actually, let's force HTTPS in .env if used by AppServiceProvider, but standard is just APP_URL
        
        print("--- Clearing Cache ---")
        cmds = [
            f"cd {APP_DIR} && php artisan config:clear",
            f"cd {APP_DIR} && php artisan view:clear",
            f"cd {APP_DIR} && php artisan route:clear",
            f"cd {APP_DIR} && php artisan optimize"
        ]
        
        for cmd in cmds:
            stdin, stdout, stderr = ssh.exec_command(cmd)
            print(stdout.read().decode())
        
        print("--- Verify .env ---")
        stdin, stdout, stderr = ssh.exec_command(f"grep APP_URL {APP_DIR}/.env")
        print(stdout.read().decode())

    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
