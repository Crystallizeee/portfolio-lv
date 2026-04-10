import paramiko
import os
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

REMOTE_VIEW_PATH = "/var/www/portfolio/resources/views/livewire/admin/manage-posts.blade.php"
LOCAL_VIEW_PATH = r"d:\src_code\Py\Portfolio\portfolio-lv\resources\views\livewire\admin\manage-posts.blade.php"
REMOTE_BLOG_PATH = "/var/www/portfolio/resources/views/blog/show.blade.php"
LOCAL_BLOG_PATH = r"d:\src_code\Py\Portfolio\portfolio-lv\resources\views\blog\show.blade.php"
REMOTE_INDEX_PATH = "/var/www/portfolio/resources/views/blog/index.blade.php"
LOCAL_INDEX_PATH = r"d:\src_code\Py\Portfolio\portfolio-lv\resources\views\blog\index.blade.php"
APP_DIR = "/var/www/portfolio"

def main():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    try:
        ssh.connect(HOST, username=USERNAME, password=PASSWORD)
        sftp = ssh.open_sftp()
        
        print(f"Uploading {LOCAL_VIEW_PATH}...")
        sftp.put(LOCAL_VIEW_PATH, REMOTE_VIEW_PATH)
        
        print(f"Uploading {LOCAL_BLOG_PATH}...")
        sftp.put(LOCAL_BLOG_PATH, REMOTE_BLOG_PATH)

        print(f"Uploading {LOCAL_INDEX_PATH}...")
        sftp.put(LOCAL_INDEX_PATH, REMOTE_INDEX_PATH)
        
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
