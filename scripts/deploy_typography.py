import paramiko
import os
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

APP_DIR = "/var/www/portfolio"

FILES_TO_UPLOAD = [
    (r"d:\src_code\Py\Portfolio\portfolio-lv\package.json", "/var/www/portfolio/package.json"),
    (r"d:\src_code\Py\Portfolio\portfolio-lv\package-lock.json", "/var/www/portfolio/package-lock.json"),
    (r"d:\src_code\Py\Portfolio\portfolio-lv\resources\css\app.css", "/var/www/portfolio/resources/css/app.css"),
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
        
        print("--- Installing Dependencies ---")
        # Ensure we are in the correct directory and run npm install
        # Redirecting stderr to stdout to catch any error messages
        stdin, stdout, stderr = ssh.exec_command(f"cd {APP_DIR} && npm install")
        print(stdout.read().decode())
        print(stderr.read().decode())
        
        print("--- Building Assets ---")
        stdin, stdout, stderr = ssh.exec_command(f"cd {APP_DIR} && npm run build")
        print(stdout.read().decode())
        print(stderr.read().decode())
        
        print("--- Clearing View Cache ---")
        ssh.exec_command(f"cd {APP_DIR} && php artisan view:clear")
        
        print("Done.")

    except Exception as e:
        print(f"Error: {e}")
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
