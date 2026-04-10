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
        
        print("--- Setting PHP 8.3 as default ---")
        ssh.exec_command("update-alternatives --set php /usr/bin/php8.3")
        ssh.exec_command("update-alternatives --set phar /usr/bin/phar8.3")
        ssh.exec_command("update-alternatives --set phar.phar /usr/bin/phar.phar8.3")
        
        print("--- Verify PHP Version ---")
        stdin, stdout, stderr = ssh.exec_command("php -v")
        print(stdout.read().decode())
        
        print("--- PHP Modules (Zip check) ---")
        stdin, stdout, stderr = ssh.exec_command("php -m | grep zip")
        print(f"Zip module: {stdout.read().decode().strip()}")
        
        print("--- Composer Install (Final Attempt) ---")
        cmd = f"cd {APP_DIR} && composer install --no-dev --optimize-autoloader -v"
        stdin, stdout, stderr = ssh.exec_command(cmd)
        
        # Stream output
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
