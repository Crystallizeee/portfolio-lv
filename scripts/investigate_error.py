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
        
        print("--- Vendor Autoload Check ---")
        stdin, stdout, stderr = ssh.exec_command(f"ls -l {APP_DIR}/vendor/autoload.php")
        print(stdout.read().decode())
        print(stderr.read().decode())

        print("--- PHP Fatal Error Details ---")
        # Grep for "Fatal error" and get the last match
        cmd = "grep 'PHP Fatal error' /var/log/nginx/error.log | tail -n 1"
        stdin, stdout, stderr = ssh.exec_command(cmd)
        print(stdout.read().decode())
        
    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
