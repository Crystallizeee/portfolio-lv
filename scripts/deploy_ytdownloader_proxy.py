import paramiko
import sys
from config import HOST as GATEWAY_IP, USERNAME as GATEWAY_USER, PASSWORD as GATEWAY_PASS, REMOTE_DIR

CONTAINER_IP = "192.168.1.113"
SERVICE_PORT = 80 # Assuming port 80 for now
DOMAIN = "ytdownloader.great-x-attach.xyz"

NGINX_CONFIG = f"""server {{
    listen 80;
    server_name {DOMAIN};

    location / {{
        proxy_pass http://{CONTAINER_IP}:{SERVICE_PORT};
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }}
}}
"""

def run_command(ssh, command, description):
    print(f"[{description}] Running...")
    stdin, stdout, stderr = ssh.exec_command(command)
    exit_status = stdout.channel.recv_exit_status()
    out = stdout.read().decode().strip()
    err = stderr.read().decode().strip()
    
    if exit_status != 0:
        print(f"Error: {err}")
        return False
    else:
        print("Success")
        return True

def main():
    try:
        print(f"Connecting to {GATEWAY_IP}...")
        ssh = paramiko.SSHClient()
        ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        ssh.connect(GATEWAY_IP, username=GATEWAY_USER, password=GATEWAY_PASS)
        print("Connected.")

        # 1. Create Nginx Configuration
        print(f"Creating Nginx config for {DOMAIN}...")
        config_path = f"/etc/nginx/sites-available/{DOMAIN}"
        create_cmd = f"echo '{NGINX_CONFIG}' > {config_path}"
        if run_command(ssh, create_cmd, "Create Config File"):
            
            # 2. Enable Site (Symlink)
            link_cmd = f"ln -sf {config_path} /etc/nginx/sites-enabled/"
            run_command(ssh, link_cmd, "Enable Site")

            # 3. Test and Reload Nginx
            if run_command(ssh, "nginx -t", "Test Nginx Config"):
                run_command(ssh, "systemctl reload nginx", "Reload Nginx")
                print(f"\n✅ {DOMAIN} configured to proxy to {CONTAINER_IP}:{SERVICE_PORT}")
            else:
                print("\n❌ Nginx config test failed. Reverting...")
                run_command(ssh, f"rm {config_path}", "Remove Config")
                run_command(ssh, f"rm /etc/nginx/sites-enabled/{DOMAIN}", "Remove Symlink")
        
        ssh.close()

    except Exception as e:
        print(f"An error occurred: {e}")

if __name__ == "__main__":
    main()
