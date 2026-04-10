import paramiko
import sys
from config import HOST as SERVER, USERNAME as USER, PASSWORD, REMOTE_DIR

def deploy():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    try:
        print(f"Connecting to {SERVER} using password...")
        ssh.connect(SERVER, username=USER, password=PASSWORD, timeout=10)
    except Exception as e:
        print(f"Connection failed: {e}")
        return

    print("Connected. Checking for git repository...")
    
    # Check if .git exists
    stdin, stdout, stderr = ssh.exec_command(f'test -d {REMOTE_DIR}/.git && echo "EXISTS" || echo "MISSING"')
    if stdout.read().decode().strip() != 'EXISTS':
        print(f"Error: {REMOTE_DIR} is not a git repository.")
        ssh.close()
        return

    print("Git repository found. Cleaning local modifications...")
    
    # Configure git to ignore filemode changes (common cause of conflicts)
    ssh.exec_command(f'cd {REMOTE_DIR} && git config core.filemode false')
    
    # Fetch latest changes from remote
    print("-> Fetching latest changes (git fetch origin)...")
    ssh.exec_command(f'cd {REMOTE_DIR} && git fetch origin')

    # Reset local changes
    print("-> Resetting local changes (git reset --hard origin/main)...")
    stdin, stdout, stderr = ssh.exec_command(f'cd {REMOTE_DIR} && git reset --hard origin/main')
    reset_out = stdout.read().decode().strip()
    reset_err = stderr.read().decode().strip()
    if reset_out: print(f"  {reset_out}")
    if reset_err: print(f"  {reset_err}")

    print("-> Cleaning untracked files (git clean -fd)...")
    stdin, stdout, stderr = ssh.exec_command(f'cd {REMOTE_DIR} && git clean -fd')
    clean_out = stdout.read().decode().strip()
    if clean_out: print(f"  {clean_out}")

    print("Pulling latest changes...")
    
    # Run git pull
    stdin, stdout, stderr = ssh.exec_command(f'cd {REMOTE_DIR} && git pull origin main')
    out = stdout.read().decode().strip()
    err = stderr.read().decode().strip()
    
    print("--- Git Pull Output ---")
    if out: print(out)
    if err: print(f"Stderr: {err}")
    print("-----------------------")
    
    if "Already up to date." in out:
        print("Server is already up to date.")
    elif "Updating" in out or "Fast-forward" in out or "HEAD is now at" in reset_out:
        print("Changes pulled successfully. Running post-deployment tasks...")
        
        commands = [
            ('Migrating database', f'cd {REMOTE_DIR} && php artisan migrate --force'),
            ('Clearing cache', f'cd {REMOTE_DIR} && php artisan optimize:clear'),
            ('Installing dependencies', f'cd {REMOTE_DIR} && composer install --no-dev --optimize-autoloader'),
            ('Restaring queue (if any)', f'cd {REMOTE_DIR} && php artisan queue:restart'),
        ]
        
        for desc, cmd in commands:
            print(f"\n- {desc}...")
            stdin, stdout, stderr = ssh.exec_command(cmd)
            out = stdout.read().decode().strip()
            err = stderr.read().decode().strip()
            if out: print(f"  {out}")
            if err: print(f"  Warning/Error: {err}")
            
    else:
        print("Git pull might have failed or required manual intervention.")

    ssh.close()
    print("\nDeployment finished.")

if __name__ == '__main__':
    deploy()
