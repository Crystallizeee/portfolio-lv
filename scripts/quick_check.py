import paramiko
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST


def run(ssh, cmd):
    print(f"\n$ {cmd}")
    channel = ssh.get_transport().open_session()
    channel.settimeout(8)
    channel.exec_command(cmd)
    try:
        out = b""
        while True:
            chunk = channel.recv(4096)
            if not chunk:
                break
            out += chunk
        print(out.decode().strip())
    except Exception:
        pass
    try:
        err = channel.recv_stderr(4096).decode().strip()
        if err:
            print(f"STDERR: {err}")
    except:
        pass
    channel.close()

def main():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    ssh.connect(HOST, username=USERNAME, password=PASSWORD)
    print("Connected.")

    run(ssh, "systemctl is-active cloudflared")
    run(ssh, "journalctl -u cloudflared --no-pager -n 15")
    run(ssh, "cat /etc/cloudflared/config.yml")
    run(ssh, "curl -s -o /dev/null -w '%{http_code}' -H 'Host: great-x-attach.xyz' http://localhost")
    run(ssh, "curl -s -o /dev/null -w '%{http_code}' -H 'Host: portfolio.great-x-attach.xyz' http://localhost")

    ssh.close()
    print("\nDone.")

if __name__ == "__main__":
    main()
