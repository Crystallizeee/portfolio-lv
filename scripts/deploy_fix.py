import paramiko
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

REMOTE_PATH = "/var/www/portfolio/app/Livewire/Admin/ManageProjects.php"
LOCAL_PATH = r"d:\src_code\Py\Portfolio\portfolio-lv\app\Livewire\Admin\ManageProjects.php"

def main():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    try:
        ssh.connect(HOST, username=USERNAME, password=PASSWORD)
        
        print(f"Uploading {LOCAL_PATH} to {REMOTE_PATH}...")
        sftp = ssh.open_sftp()
        sftp.put(LOCAL_PATH, REMOTE_PATH)
        sftp.close()
        print("Upload complete.")

    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
