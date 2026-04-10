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
        
        cmds = [
            (f"cd {APP_DIR} && php artisan migrate --force", "Running Migrations"),
            (f"cd {APP_DIR} && php artisan optimize", "Optimizing"),
            (f"cd {APP_DIR} && php artisan view:cache", "Caching Views"),
            (f"chown -R www-data:www-data {APP_DIR}", "Fixing Permissions (Final)"),
            (f"chmod -R 775 {APP_DIR}/storage {APP_DIR}/bootstrap/cache", "Fixing Storage Permissions")
        ]
        
        for cmd, desc in cmds:
            print(f"--- {desc} ---")
            stdin, stdout, stderr = ssh.exec_command(cmd)
            out = stdout.read().decode().strip()
            err = stderr.read().decode().strip()
            print(out)
            if err:
                print(f"Error/Stderr: {err}")

        # Final Verification
        print("--- Final Local Curl Check ---")
        stdin, stdout, stderr = ssh.exec_command("curl -I http://localhost")
        print(stdout.read().decode())
        
    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
