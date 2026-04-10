import paramiko
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

REMOTE_PATH = "/var/www/portfolio/app/Livewire/ServerStatus.php"
LOCAL_PATH = r"d:\src_code\Py\Portfolio\portfolio-lv\app\Livewire\ServerStatus.php"
APP_DIR = "/var/www/portfolio"

def main():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    try:
        ssh.connect(HOST, username=USERNAME, password=PASSWORD)
        
        print(f"Uploading {LOCAL_PATH} to {REMOTE_PATH}...")
        sftp = ssh.open_sftp()
        sftp.put(LOCAL_PATH, REMOTE_PATH)
        sftp.close()
        
        print("--- Clearing Cache ---")
        ssh.exec_command(f"cd {APP_DIR} && php artisan config:clear && php artisan view:clear")
        
        print("--- Verifying Landing Page CSS ---")
        # Curl the landing page and look for CSS links
        stdin, stdout, stderr = ssh.exec_command(f"curl -s https://portfolio.great-x-attach.xyz | grep css")
        output = stdout.read().decode()
        print(output)
        
        if not output:
             print("No CSS links found in https response. Trying localhost...")
             stdin, stdout, stderr = ssh.exec_command(f"curl -s http://localhost | grep css")
             print(stdout.read().decode())

    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
