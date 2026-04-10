import paramiko
import time
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST


def main():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    try:
        ssh.connect(HOST, username=USERNAME, password=PASSWORD)
        
        # Stop Apache2
        print("Stopping Apache2...")
        ssh.exec_command("systemctl stop apache2")
        ssh.exec_command("systemctl disable apache2")
        
        # Restart Nginx
        print("Restarting Nginx...")
        stdin, stdout, stderr = ssh.exec_command("systemctl restart nginx")
        err = stderr.read().decode()
        if err:
            print(f"Nginx restart error: {err}")
        else:
            print("Nginx restarted.")
            
        # Verify
        print("Verifying site access...")
        stdin, stdout, stderr = ssh.exec_command("curl -I http://localhost")
        print(stdout.read().decode())
        
        # Check cloudflared again to be sure
        print("Checking Cloudflared...")
        stdin, stdout, stderr = ssh.exec_command("systemctl status cloudflared --no-pager")
        print(stdout.read().decode())

    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
