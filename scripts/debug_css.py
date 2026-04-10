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
        
        print("--- Listing public/build ---")
        stdin, stdout, stderr = ssh.exec_command(f"ls -R {APP_DIR}/public/build")
        print(stdout.read().decode())
        
        print("--- Checking .env APP_URL ---")
        stdin, stdout, stderr = ssh.exec_command(f"grep APP_URL {APP_DIR}/.env")
        print(stdout.read().decode())
        
        print("--- Fetching Homepage HTML (Head) ---")
        stdin, stdout, stderr = ssh.exec_command("curl -k https://localhost | head -n 30")
        print(stdout.read().decode())
        
    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
