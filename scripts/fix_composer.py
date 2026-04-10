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
        
        print("Installing unzip...")
        ssh.exec_command("DEBIAN_FRONTEND=noninteractive apt-get install -y unzip")
        
        print("Running composer install...")
        stdin, stdout, stderr = ssh.exec_command(f"cd {APP_DIR} && composer install --no-dev --optimize-autoloader -v")
        
        # Read output in real-time or chunk
        while True:
            line = stdout.readline()
            if not line:
                break
            print(line.strip())
            
        print("Stderr:")
        print(stderr.read().decode())
        
    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
