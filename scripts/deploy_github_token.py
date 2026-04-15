import paramiko
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR, GITHUB_USERNAME, GITHUB_TOKEN

def run_command(ssh, command):
    print(f"\n[Running] {command}")
    stdin, stdout, stderr = ssh.exec_command(command)
    exit_status = stdout.channel.recv_exit_status()
    
    out = stdout.read().decode().strip()
    err = stderr.read().decode().strip()
    
    if out:
        print(f"[Output]\n{out}")
    if err:
        print(f"[Error]\n{err}")
        
    return exit_status == 0

def deploy_github_token():
    print(f"Connecting to {HOST}...")
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    
    try:
        ssh.connect(HOST, username=USERNAME, password=PASSWORD)
        print("Connected successfully!")
        
        # Pull latest changes to ensure livewire component is there
        run_command(ssh, f"cd {REMOTE_DIR} && git pull origin main")
        
        # Append GitHub credentials if they don't exist
        env_file = f"{REMOTE_DIR}/.env"
        
        # Check if already present
        print("Checking for existing GitHub config...")
        stdin, stdout, stderr = ssh.exec_command(f"grep 'GITHUB_USERNAME' {env_file}")
        if stdout.read().decode().strip():
            print("GitHub credentials already exist in .env. Replacing them...")
            # Remove existing lines to cleanly replace
            run_command(ssh, f"sed -i '/GITHUB_USERNAME/d' {env_file}")
            run_command(ssh, f"sed -i '/GITHUB_TOKEN/d' {env_file}")
            
        print("Injecting new GitHub Config to .env...")
        run_command(ssh, f"echo '' >> {env_file}")
        run_command(ssh, f"echo '# GitHub Configuration' >> {env_file}")
        run_command(ssh, f"echo 'GITHUB_USERNAME={GITHUB_USERNAME}' >> {env_file}")
        run_command(ssh, f"echo 'GITHUB_TOKEN={GITHUB_TOKEN}' >> {env_file}")
        
        print("\nClearing Server Caches...")
        run_command(ssh, f"cd {REMOTE_DIR} && php artisan view:clear")
        run_command(ssh, f"cd {REMOTE_DIR} && php artisan config:clear")
        run_command(ssh, f"cd {REMOTE_DIR} && php artisan cache:clear")

        print("\nDeployment completed successfully! GitHub integration is now active in production.")
        
    except Exception as e:
        print(f"Deployment failed: {e}")
    finally:
        ssh.close()
        print("Connection closed.")

if __name__ == "__main__":
    deploy_github_token()
