import paramiko
import os
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

APP_DIR = "/var/www/portfolio"

FILES_TO_UPLOAD = [
    (r"d:\src_code\Py\Portfolio\portfolio-lv\database\migrations\2026_02_10_204828_create_job_applications_table.php", "/var/www/portfolio/database/migrations/2026_02_10_204828_create_job_applications_table.php"),
    (r"d:\src_code\Py\Portfolio\portfolio-lv\app\Models\JobApplication.php", "/var/www/portfolio/app/Models/JobApplication.php"),
    (r"d:\src_code\Py\Portfolio\portfolio-lv\app\Livewire\Admin\JobTracker.php", "/var/www/portfolio/app/Livewire/Admin/JobTracker.php"),
    (r"d:\src_code\Py\Portfolio\portfolio-lv\resources\views\livewire\admin\job-tracker.blade.php", "/var/www/portfolio/resources/views/livewire/admin/job-tracker.blade.php"),
    (r"d:\src_code\Py\Portfolio\portfolio-lv\routes\web.php", "/var/www/portfolio/routes/web.php"),
    (r"d:\src_code\Py\Portfolio\portfolio-lv\resources\views\layouts\admin.blade.php", "/var/www/portfolio/resources/views/layouts/admin.blade.php"),
]

def main():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    try:
        ssh.connect(HOST, username=USERNAME, password=PASSWORD)
        sftp = ssh.open_sftp()
        
        for local, remote in FILES_TO_UPLOAD:
            print(f"Uploading {os.path.basename(local)}...")
            sftp.put(local, remote)
        
        sftp.close()
        
        print("--- Running Migration ---")
        ssh.exec_command(f"cd {APP_DIR} && php artisan migrate --force")
        
        print("--- Clearing Caches ---")
        ssh.exec_command(f"cd {APP_DIR} && php artisan optimize:clear")
        
        print("Done.")

    except Exception as e:
        print(f"Error: {e}")
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
