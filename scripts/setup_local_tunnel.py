import paramiko
import json
import time
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

TUNNEL_ID = "769a95dc-29b1-489a-ba65-aff0f74f8c31"
ACCOUNT_TAG = "29aa5035a6f089ec1b9bd6f9bd4b94d4"
TUNNEL_SECRET = "+8QN6071gVIBZi/+uO8WTtG9OkVPYJhilxfMuj3Gjz0="

# Credentials file for locally managed tunnel
CREDENTIALS = json.dumps({
    "AccountTag": ACCOUNT_TAG,
    "TunnelSecret": TUNNEL_SECRET,
    "TunnelID": TUNNEL_ID
})

# Config with ingress rules
CONFIG = f"""tunnel: {TUNNEL_ID}
credentials-file: /etc/cloudflared/{TUNNEL_ID}.json

ingress:
  - hostname: portfolio.great-x-attach.xyz
    service: http://localhost:80
  - hostname: great-x-attach.xyz
    service: http://localhost:80
  - service: http_status:404
"""

# Systemd service file (locally managed, no token)
SERVICE = f"""[Unit]
Description=cloudflared tunnel
After=network-online.target
Wants=network-online.target

[Service]
Type=notify
TimeoutStartSec=0
ExecStart=/usr/bin/cloudflared --no-autoupdate --config /etc/cloudflared/config.yml tunnel run
Restart=on-failure
RestartSec=5

[Install]
WantedBy=multi-user.target
"""

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
    print(f"  Exit code: {exit_status}")
    return exit_status == 0, out

def main():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    ssh.connect(HOST, username=USERNAME, password=PASSWORD)
    print("Connected.\n")

    # 1. Stop cloudflared
    print("=" * 50)
    print("Step 1: Stopping cloudflared service")
    print("=" * 50)
    run_command(ssh, "systemctl stop cloudflared", "Stopping cloudflared")

    # 2. Create config directory
    print("\n" + "=" * 50)
    print("Step 2: Writing config files")
    print("=" * 50)
    run_command(ssh, "mkdir -p /etc/cloudflared", "Creating config directory")
    
    # Write credentials file
    sftp = ssh.open_sftp()
    creds_path = f"/etc/cloudflared/{TUNNEL_ID}.json"
    with sftp.file(creds_path, "w") as f:
        f.write(CREDENTIALS)
    print(f"\n  Written credentials to {creds_path}")
    
    # Write config.yml
    with sftp.file("/etc/cloudflared/config.yml", "w") as f:
        f.write(CONFIG)
    print("  Written config to /etc/cloudflared/config.yml")
    sftp.close()

    # 3. Update systemd service
    print("\n" + "=" * 50)
    print("Step 3: Updating systemd service")
    print("=" * 50)
    sftp = ssh.open_sftp()
    with sftp.file("/etc/systemd/system/cloudflared.service", "w") as f:
        f.write(SERVICE)
    sftp.close()
    print("  Written systemd service file")
    
    run_command(ssh, "systemctl daemon-reload", "Reloading systemd")

    # 4. Validate config
    print("\n" + "=" * 50)
    print("Step 4: Validating tunnel config")
    print("=" * 50)
    run_command(ssh, "cloudflared tunnel --config /etc/cloudflared/config.yml ingress validate", "Validating ingress rules")

    # 5. Start cloudflared
    print("\n" + "=" * 50)
    print("Step 5: Starting cloudflared")
    print("=" * 50)
    run_command(ssh, "systemctl start cloudflared", "Starting cloudflared")
    
    time.sleep(5)
    run_command(ssh, "systemctl status cloudflared --no-pager -l", "Checking status")
    
    # 6. Test locally
    print("\n" + "=" * 50)
    print("Step 6: Testing locally on server")
    print("=" * 50)
    run_command(ssh, "curl -s -o /dev/null -w '%{http_code}' -H 'Host: great-x-attach.xyz' http://localhost", "Testing root domain via localhost")
    run_command(ssh, "curl -s -o /dev/null -w '%{http_code}' -H 'Host: portfolio.great-x-attach.xyz' http://localhost", "Testing portfolio subdomain via localhost")

    ssh.close()
    print("\n✅ Done! Cloudflared now running with local config.")
    print("Both great-x-attach.xyz and portfolio.great-x-attach.xyz should work now.")

if __name__ == "__main__":
    main()
