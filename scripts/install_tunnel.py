import paramiko
import time
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

TOKEN = "eyJhIjoiMjlhYTUwMzVhNmYwODllYzFiOWJkNmY5YmQ0Yjk0ZDQiLCJ0IjoiNzY5YTk1ZGMtMjliMS00ODlhLWJhNjUtYWZmMGY3NGY4YzMxIiwicyI6Iis4UU42MDcxZ1ZJQlppLyt1TzhXVHRHOU9rVlBZSmhpbHhmTXVqM0dqNzA9In0="

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

    # Install cloudflared as a service with the token
    run_command(ssh, f"cloudflared service install {TOKEN}", "Installing cloudflared service")
    
    # Start the service
    run_command(ssh, "systemctl start cloudflared", "Starting cloudflared service")
    
    # Wait a moment for it to connect
    print("\nWaiting 5 seconds for tunnel to connect...")
    time.sleep(5)
    
    # Check status
    run_command(ssh, "systemctl status cloudflared --no-pager -l", "Checking cloudflared status")
    
    ssh.close()
    print("\n✅ Done! Check Cloudflare Dashboard to verify tunnel is HEALTHY.")

if __name__ == "__main__":
    main()
