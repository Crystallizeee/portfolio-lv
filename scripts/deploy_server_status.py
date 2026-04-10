import paramiko
import os
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST


# Files to deploy
FILES = [
    {
        "local": r"d:\src_code\Py\Portfolio\portfolio-lv\app\Livewire\ServerStatus.php",
        "remote": "/var/www/portfolio/app/Livewire/ServerStatus.php"
    },
    {
        "local": r"d:\src_code\Py\Portfolio\portfolio-lv\resources\views\livewire\server-status.blade.php",
        "remote": "/var/www/portfolio/resources/views/livewire/server-status.blade.php"
    }
]

APP_DIR = "/var/www/portfolio"

def main():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    try:
        ssh.connect(HOST, username=USERNAME, password=PASSWORD)
        sftp = ssh.open_sftp()
        
        for file in FILES:
            print(f"Uploading {file['local']}...")
            sftp.put(file['local'], file['remote'])
        
        sftp.close()
        
        print("--- Clearing View Cache ---")
        ssh.exec_command(f"cd {APP_DIR} && php artisan view:clear")
        
        print("Done.")

    except Exception as e:
        print(f"Error: {e}")
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
