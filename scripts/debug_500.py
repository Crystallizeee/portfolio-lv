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
        
        print("--- Permissions Check ---")
        stdin, stdout, stderr = ssh.exec_command(f"ls -la {APP_DIR}/storage {APP_DIR}/bootstrap/cache")
        print(stdout.read().decode())

        print("--- Nginx Error Log (Last 20 lines) ---")
        stdin, stdout, stderr = ssh.exec_command("tail -n 20 /var/log/nginx/error.log")
        print(stdout.read().decode())
        
        print("--- Laravel Log (Last 50 lines) ---")
        stdin, stdout, stderr = ssh.exec_command(f"tail -n 50 {APP_DIR}/storage/logs/laravel.log")
        print(stdout.read().decode())
        
    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
