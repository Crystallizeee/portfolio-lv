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
        
        print("--- PHP Modules ---")
        stdin, stdout, stderr = ssh.exec_command("php -m | grep zip")
        print(f"Zip module: {stdout.read().decode().strip()}")
        
        print("--- PHP Version ---")
        stdin, stdout, stderr = ssh.exec_command("php -v")
        print(stdout.read().decode())
        
        print("--- Composer Install (Retry) ---")
        # Try running composer with ignore-platform-reqs as a fallback to get things moving if it's just a strict check
        # But also try standard first
        cmd = f"cd {APP_DIR} && composer install --no-dev --optimize-autoloader -v"
        stdin, stdout, stderr = ssh.exec_command(cmd)
        print(stdout.read().decode())
        err = stderr.read().decode()
        print(err)
        
        if "ext-zip" in err:
            print("Retrying with --ignore-platform-req=ext-zip...")
            cmd = f"cd {APP_DIR} && composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-zip -v"
            stdin, stdout, stderr = ssh.exec_command(cmd)
            print(stdout.read().decode())
            print(stderr.read().decode())

    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
