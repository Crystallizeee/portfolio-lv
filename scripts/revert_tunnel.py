import paramiko
import time
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

TOKEN = "eyJhIjoiMjlhYTUwMzVhNmYwODllYzFiOWJkNmY5YmQ0Yjk0ZDQiLCJ0IjoiNzY5YTk1ZGMtMjliMS00ODlhLWJhNjUtYWZmMGY3NGY4YzMxIiwicyI6Iis4UU42MDcxZ1ZJQlppLyt1TzhXVHRHOU9rVlBZSmhpbHhmTXVqM0dqNzA9In0="

# Revert to remotely managed (token-based) service
SERVICE = f"""[Unit]
Description=cloudflared tunnel
After=network-online.target
Wants=network-online.target

[Service]
Type=notify
TimeoutStartSec=0
ExecStart=/usr/bin/cloudflared --no-autoupdate tunnel run --token {TOKEN}
Restart=on-failure
RestartSec=5

[Install]
WantedBy=multi-user.target
"""

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
        result = out.decode().strip()
        if result:
            print(f"  {result}")
        return result
    except Exception as e:
        print(f"  (timeout/error: {e})")
        return ""

def main():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    ssh.connect(HOST, username=USERNAME, password=PASSWORD)
    print("Connected.\n")

    # 1. Stop cloudflared
    print("=" * 50)
    print("Step 1: Reverting to token-based tunnel")
    print("=" * 50)
    run(ssh, "systemctl stop cloudflared")
    
    # Remove local config to avoid conflicts
    run(ssh, "rm -f /etc/cloudflared/config.yml")
    print("  Removed local config.yml")
    
    # Write token-based service file
    sftp = ssh.open_sftp()
    with sftp.file("/etc/systemd/system/cloudflared.service", "w") as f:
        f.write(SERVICE)
    sftp.close()
    print("  Written token-based service file")
    
    run(ssh, "systemctl daemon-reload")
    run(ssh, "systemctl start cloudflared")
    
    print("\nWaiting 8 seconds for tunnel to connect...")
    time.sleep(8)
    
    # 2. Check status
    print("\n" + "=" * 50)
    print("Step 2: Checking status")
    print("=" * 50)
    run(ssh, "systemctl is-active cloudflared")
    run(ssh, "journalctl -u cloudflared --no-pager -n 10 --since '1 min ago'")
    
    # 3. Check DNS records 
    print("\n" + "=" * 50)
    print("Step 3: DNS checks")
    print("=" * 50)
    run(ssh, "dig +short great-x-attach.xyz @1.1.1.1")
    run(ssh, "dig +short portfolio.great-x-attach.xyz @1.1.1.1")
    run(ssh, "dig +short CNAME great-x-attach.xyz @1.1.1.1")
    run(ssh, "dig +short CNAME portfolio.great-x-attach.xyz @1.1.1.1")

    ssh.close()
    print("\nDone.")

if __name__ == "__main__":
    main()
