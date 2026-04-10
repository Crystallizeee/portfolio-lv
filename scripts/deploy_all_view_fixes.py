import paramiko
import os
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

BASE_LOCAL = r"d:\src_code\Py\Portfolio\portfolio-lv"
BASE_REMOTE = "/var/www/portfolio"

FILES = [
    "resources/views/livewire/admin/manage-certificates.blade.php",
    "resources/views/livewire/admin/manage-experiences.blade.php",
    "resources/views/livewire/admin/manage-languages.blade.php",
    "resources/views/livewire/admin/manage-posts.blade.php",
    "resources/views/livewire/admin/manage-skills.blade.php",
    "resources/views/livewire/admin/profile-settings.blade.php"
]

def main():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    try:
        ssh.connect(HOST, username=USERNAME, password=PASSWORD)
        sftp = ssh.open_sftp()
        
        for file in FILES:
            local_path = os.path.join(BASE_LOCAL, file.replace("/", "\\"))
            remote_path = f"{BASE_REMOTE}/{file}"
            print(f"Uploading {local_path} to {remote_path}...")
            sftp.put(local_path, remote_path)
        
        sftp.close()
        
        print("--- Clearing View Cache ---")
        ssh.exec_command(f"cd {BASE_REMOTE} && php artisan view:clear")
        
        print("Done.")

    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
