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
        
        print("--- Fetching Homepage HTML (HTTP) ---")
        # Grep for .css and see if it has https
        stdin, stdout, stderr = ssh.exec_command("curl -s http://localhost | grep .css")
        print(stdout.read().decode())
        
    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
