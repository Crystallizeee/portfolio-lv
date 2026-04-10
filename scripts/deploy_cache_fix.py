import paramiko
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

REMOTE_ROUTES_PATH = "/var/www/portfolio/routes/web.php"
LOCAL_ROUTES_PATH = r"d:\src_code\Py\Portfolio\portfolio-lv\routes\web.php"
REMOTE_LAYOUT_PATH = "/var/www/portfolio/resources/views/layouts/admin.blade.php"
LOCAL_LAYOUT_PATH = r"d:\src_code\Py\Portfolio\portfolio-lv\resources\views\layouts\admin.blade.php"
APP_DIR = "/var/www/portfolio"

def main():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    try:
        ssh.connect(HOST, username=USERNAME, password=PASSWORD)
        
        print(f"Uploading routes to {REMOTE_ROUTES_PATH}...")
        sftp = ssh.open_sftp()
        sftp.put(LOCAL_ROUTES_PATH, REMOTE_ROUTES_PATH)
        
        print(f"Uploading layout to {REMOTE_LAYOUT_PATH}...")
        sftp.put(LOCAL_LAYOUT_PATH, REMOTE_LAYOUT_PATH)
        sftp.close()
        
        print("--- Clearing Route & View Cache ---")
        ssh.exec_command(f"cd {APP_DIR} && php artisan route:clear && php artisan view:clear")
        
        print("Done.")

    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
