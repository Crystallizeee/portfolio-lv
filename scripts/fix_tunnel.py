import paramiko
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST


def run_command(ssh, command, description):
    print(f"\n[{description}] Running: {command}")
    stdin, stdout, stderr = ssh.exec_command(command)
    exit_status = stdout.channel.recv_exit_status()
    out = stdout.read().decode().strip()
    err = stderr.read().decode().strip()
    if out:
        print(f"  STDOUT: {out}")
    if err:
        print(f"  STDERR: {err}")
    print(f"  Exit code: {exit_status}")
    return exit_status == 0, out

def main():
    print(f"Connecting to {HOST}...")
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    
    try:
        ssh.connect(HOST, username=USERNAME, password=PASSWORD)
        print("Connected.")
    except Exception as e:
        print(f"Connection failed: {e}")
        return

    # Check if cloudflared is installed
    run_command(ssh, "which cloudflared", "Checking cloudflared binary")
    run_command(ssh, "cloudflared --version", "Checking cloudflared version")
    
    # Check service status
    run_command(ssh, "systemctl status cloudflared --no-pager -l", "Checking cloudflared service status")
    
    # Try to restart
    success, _ = run_command(ssh, "systemctl restart cloudflared", "Restarting cloudflared service")
    
    if success:
        import time
        time.sleep(3)
        run_command(ssh, "systemctl status cloudflared --no-pager -l", "Verifying cloudflared is running")
    else:
        print("\n⚠️ Restart failed. Cloudflared mungkin belum di-install sebagai service.")
        print("Coba jalankan ulang dengan token dari Cloudflare Dashboard.")
        print("Pilih 'Debian' di dashboard, lalu copy command-nya.")
        print("Command-nya kurang lebih: cloudflared service install <TOKEN>")

    ssh.close()

if __name__ == "__main__":
    main()
