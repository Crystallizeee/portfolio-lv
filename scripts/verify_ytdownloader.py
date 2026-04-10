import paramiko
import requests
import time
import sys
from config import HOST as GATEWAY_IP, USERNAME as GATEWAY_USER, PASSWORD as GATEWAY_PASS, REMOTE_DIR

DOMAIN = "ytdownloader.great-x-attach.xyz"

def main():
    print(f"Verifying {DOMAIN}...")

    # 1. Verify Nginx Proxy on Gateway
    print("\n[Step 1] Verifying Nginx Proxy configuration on Gateway...")
    try:
        ssh = paramiko.SSHClient()
        ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        ssh.connect(GATEWAY_IP, username=GATEWAY_USER, password=GATEWAY_PASS)
        
        # Check if Nginx responds to the Host header
        cmd = f"curl -I -s -H 'Host: {DOMAIN}' http://localhost | grep 'HTTP/1.1'"
        stdin, stdout, stderr = ssh.exec_command(cmd)
        response_head = stdout.read().decode().strip()
        
        if "200 OK" in response_head or "301 Moved" in response_head or "302 Found" in response_head:
            print(f"✅ Nginx on Gateway is responding to {DOMAIN}: {response_head}")
        else:
            print(f"⚠️ Nginx response: {response_head}")
            print("Note: If the container returns a non-200 code (like 401 or 403), that's still a success for Nginx proxying.")
            
        ssh.close()
    except Exception as e:
        print(f"❌ SSH Verification failed: {e}")

    # 2. Verify Cloudflare Tunnel (Public Access)
    print(f"\n[Step 2] Verifying Public Access via Cloudflare Tunnel ({DOMAIN})...")
    try:
        # Note: DNS propagation might take a moment, but Tunnel updates are usually fast.
        response = requests.get(f"https://{DOMAIN}", timeout=10)
        print(f"Status Code: {response.status_code}")
        if response.status_code < 500: # Any non-server-error is a success for reachability
            print(f"✅ {DOMAIN} is reachable publicly!")
        else:
            print(f"⚠️ Got status {response.status_code}. This might be an issue with the application itself.")
    except Exception as e:
        print(f"❌ Public Verification failed: {e}")

if __name__ == "__main__":
    main()
