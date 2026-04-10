import paramiko
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST


def run_command(ssh, command, description):
    print(f"\n[{description}]")
    print(f"  $ {command}")
    stdin, stdout, stderr = ssh.exec_command(command)
    exit_status = stdout.channel.recv_exit_status()
    out = stdout.read().decode().strip()
    err = stderr.read().decode().strip()
    if out:
        print(f"  {out}")
    if err:
        print(f"  STDERR: {err}")
    return exit_status == 0, out

def main():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    ssh.connect(HOST, username=USERNAME, password=PASSWORD)
    print("Connected.\n")

    # Check cloudflared config locations
    run_command(ssh, "cat /etc/cloudflared/config.yml 2>/dev/null || echo 'NOT FOUND'", "Main config file")
    run_command(ssh, "cat /root/.cloudflared/config.yml 2>/dev/null || echo 'NOT FOUND'", "User config file")
    run_command(ssh, "find / -name 'config.yml' -path '*cloudflared*' 2>/dev/null", "Finding all cloudflared configs")
    run_command(ssh, "cat /etc/systemd/system/cloudflared.service 2>/dev/null || systemctl cat cloudflared 2>/dev/null", "Cloudflared service file")
    run_command(ssh, "ls -la /etc/cloudflared/ 2>/dev/null", "Listing /etc/cloudflared/")
    run_command(ssh, "ls -la /root/.cloudflared/ 2>/dev/null", "Listing ~/.cloudflared/")

    ssh.close()

if __name__ == "__main__":
    main()
