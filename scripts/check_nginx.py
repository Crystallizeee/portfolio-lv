import paramiko
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST


def main():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    try:
        ssh.connect(HOST, username=USERNAME, password=PASSWORD)
        
        cmds = [
            "nginx -t",
            "systemctl status php8.3-fpm --no-pager",
            "ls -la /var/run/php/",
            "cat /var/log/nginx/error.log | tail -n 20"
        ]
        
        for cmd in cmds:
            print(f"--- Running: {cmd} ---")
            stdin, stdout, stderr = ssh.exec_command(cmd)
            print(stdout.read().decode())
            print(stderr.read().decode())
            
    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
