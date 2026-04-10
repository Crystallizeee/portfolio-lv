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
        
        print("Restarting Nginx...")
        stdin, stdout, stderr = ssh.exec_command("systemctl restart nginx")
        exit_status = stdout.channel.recv_exit_status()
        if exit_status != 0:
            print(f"Failed: {stderr.read().decode()}")
        else:
            print("Success.")
            
        print("Status:")
        stdin, stdout, stderr = ssh.exec_command("systemctl status nginx --no-pager")
        print(stdout.read().decode())

    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
