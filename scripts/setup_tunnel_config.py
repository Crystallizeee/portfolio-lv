import paramiko
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

TUNNEL_ID = "769a95dc-29b1-489a-ba65-aff0f74f8c31"
TOKEN = "eyJhIjoiMjlhYTUwMzVhNmYwODllYzFiOWJkNmY5YmQ0Yjk0ZDQiLCJ0IjoiNzY5YTk1ZGMtMjliMS00ODlhLWJhNjUtYWZmMGY3NGY4YzMxIiwicyI6Iis4UU42MDcxZ1ZJQlppLyt1TzhXVHRHOU9rVlBZSmhpbHhmTXVqM0dqNzA9In0="

# Config with ingress rules for both domains
CONFIG_CONTENT = f"""tunnel: {TUNNEL_ID}
credentials-file: /root/.cloudflared/{TUNNEL_ID}.json

ingress:
  - hostname: portfolio.great-x-attach.xyz
    service: http://localhost:80
  - hostname: great-x-attach.xyz
    service: http://localhost:80
  - service: http_status:404
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

    # Since this is a remotely-managed tunnel (token-based),
    # the ingress rules are managed from the dashboard, not config file.
    # We need to check if there's a way to add them via CLI.
    
    # First, let's check current cloudflared tunnel info
    run_command(ssh, "cloudflared tunnel info 2>&1 || true", "Tunnel info")
    run_command(ssh, "cloudflared tunnel route dns --help 2>&1 | head -20", "DNS route help")
    
    # Try to add DNS routes via CLI
    # For remotely managed tunnels, we may need to use the API instead
    print("\n" + "=" * 50)
    print("Attempting to add DNS routes via cloudflared CLI...")
    print("=" * 50)
    
    # Route DNS for root domain
    run_command(ssh, 
        f"cloudflared tunnel route dns {TUNNEL_ID} great-x-attach.xyz 2>&1",
        "Adding DNS route for great-x-attach.xyz")
    
    # Route DNS for portfolio subdomain  
    run_command(ssh,
        f"cloudflared tunnel route dns {TUNNEL_ID} portfolio.great-x-attach.xyz 2>&1",
        "Adding DNS route for portfolio.great-x-attach.xyz")
    
    # Check the result
    run_command(ssh, "cloudflared tunnel route list 2>&1 || true", "Listing tunnel routes")

    ssh.close()
    print("\n✅ Done! Try accessing https://great-x-attach.xyz now.")

if __name__ == "__main__":
    main()
